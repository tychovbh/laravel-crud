<?php

namespace Tychovbh\LaravelCrud\Tests\App;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Tychovbh\LaravelCrud\Middleware\Authorize;
use Tychovbh\LaravelCrud\Middleware\Cache;
use Tychovbh\LaravelCrud\Middleware\CrudBindings;
use Tychovbh\LaravelCrud\Middleware\Validate;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'crud-bindings' => CrudBindings::class,
        'cache' => Cache::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'validate' => Validate::class,
        'auth' => Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
