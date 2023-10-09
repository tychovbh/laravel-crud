<?php

namespace Tychovbh\LaravelCrud\Middleware;

use Closure;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class Validate
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
        $formRequest = $this->createFormRequest($request);
        $formRequest->setContainer(app())->setRedirector(app()->make(Redirector::class));
        $formRequest->validateResolved();

        return $next($request);
    }

    /**
     * Create Form Request
     * @param Request $request
     * @return FormRequest
     */
    private function createFormRequest(Request $request): FormRequest
    {
        $name = $request->route()->getName();
        $pieces = explode('.', $name);
        $class = sprintf(
            '%sHttp\\Requests\\%s%sRequest',
            get_namespace(),
            Str::ucfirst($pieces[1]),
            Str::ucfirst(Str::singular(Str::camel($pieces[0])))
        );
        return $class::createFrom($request);
    }
}
