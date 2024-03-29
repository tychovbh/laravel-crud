<?php

namespace Tychovbh\LaravelCrud;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

use Tychovbh\LaravelCrud\Commands\MakeCrudTest;
use Tychovbh\LaravelCrud\Commands\MakeParams;
use Tychovbh\LaravelCrud\Commands\MakeRoute;
use Tychovbh\LaravelCrud\Middleware\CrudBindings;

class LaravelCrudServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-crud')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(MakeCrudTest::class)
            ->hasCommand(MakeRoute::class)
            ->hasCommand(MakeParams::class);
    }
    /**
     * Package booted.
     */
    public function packageBooted()
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->prependMiddlewareToGroup('api', CrudBindings::class);
        $router->prependMiddlewareToGroup('web', CrudBindings::class);

        Route::macro('resource', function($resource) {
            $this->defaults('resource', $resource);
        });
    }
}
