<?php

namespace Tychovbh\LaravelCrud;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tychovbh\LaravelCrud\Commands\MakeCrudTest;
use Tychovbh\LaravelCrud\Commands\MakeParams;
use Tychovbh\LaravelCrud\Commands\MakeRoute;

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
}
