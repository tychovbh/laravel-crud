<?php

namespace Tychovbh\LaravelCrud\Middleware;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Actions\ModelName;
use Tychovbh\LaravelCrud\Actions\ModelNamespace;
use Tychovbh\LaravelCrud\Actions\ModelQuery;
use Tychovbh\LaravelCrud\Actions\RequestMethod;
use ReflectionClass;
use Tychovbh\LaravelCrud\Controller;

class CrudBindings
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $action = $request->route()->getAction();

        if (!Arr::has($action, 'controller')) {
            return $next($request);
        }

        $controller = explode('@', $action['controller'])[0];

        if ($controller === Controller::class) {
            $this->bindModel($request);
            return $next($request);
        }

        $class = new ReflectionClass($controller);
        $parent = $class->getParentClass();

        if ($parent && $parent->name === Controller::class) {
            $this->bindModel($request);
        }

        return $next($request);
    }

    /**
     * Bind model
     * @param Request $request
     */
    private function bindModel(Request $request)
    {
        $model = (new ModelName())->get($request);
        Route::bind(Str::lower($model), fn(int $id) => $this->findModel($model, $id));
        Route::bind('id', fn(string $id) => $this->findModel($model, $id));
    }

    /**
     * Find model.
     * @param string $model
     * @param int $id
     * @return Model
     */
    private function findModel(string $model, string $id): Model
    {
        $model = (new ModelNamespace($model))->get();
        $method = (new RequestMethod())->get();
        $query = (new ModelQuery($model))->start(request());
        
        $emptyModel = new $model();
        $query->where($emptyModel->qualifyColumn('id'), $id);

        if (in_array($method, ['forceDestroy', 'restore'])) {
            $query->withTrashed();
        }

        return $query->firstOrFail();
    }
}
