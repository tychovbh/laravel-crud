<?php

namespace Tychovbh\LaravelCrud\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache as CacheStore;
use Illuminate\Support\Str;

class Cache
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->disabled($request)) {
            return $next($request);
        }

        $path = $this->path($request);
        $tags = $this->tags($request);

        if (CacheStore::tags($tags)->has($path)) {
            return response()->json(CacheStore::tags($tags)->get($path));
        }

        $cache_minutes = $request->cache_minutes ?? env('CRUD_CACHE_MINUTES');
        $response = $next($request);

        if (in_array($response->getStatusCode(), [200, 201])) {
            CacheStore::tags($tags)->put($path, $response->getData(true), $cache_minutes * 60);
        }

        return $response;
    }

    /**
     * Cache is disabled.
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function disabled($request): bool
    {
        if (Str::lower($request->method()) !== 'get') {
            return true;
        }

        if (!env('CRUD_CACHE_ENABLED', false)) {
            return true;
        }

        return (bool)$request->cache_disabled;
    }

    /**
     * The cache path.
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function path($request): string
    {
        $path = $request->getPathInfo();
        $params = $request->toArray();

        if ($params) {
            $path .= '?' . http_build_query($params);
        }

        return $path;
    }

    /**
     * The cache tags.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function tags($request): array
    {
        $params = $request->toArray();
        $route_name = $request->route()->getName();
        $tags = [$route_name];
        $cache_tags = $request->cache_tags;

        // TODO what about more params?
        foreach ($request->route()->parameterNames() as $name) {
            $value = $request->route($name);
            $id = is_object($value) ? $value->id: $value;
            $tags[] = sprintf('%s.%s', $route_name, $id);
        }

        if (!$cache_tags || !is_array($cache_tags)) {
            return $tags;
        }

        foreach ($request->cache_tags as $tag) {
            if (Arr::has($params, $tag) && is_string($params[$tag])) {
                $tags[] = sprintf('%s.%s.%s', $route_name, $tag, $params[$tag]);
                continue;
            }

            $tags[] = sprintf('%s.%s', $route_name, $tag);
        }

        return $tags;
    }
}
