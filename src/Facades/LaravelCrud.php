<?php

namespace Tychovbh\LaravelCrud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tychovbh\LaravelCrud\LaravelCrud
 */
class LaravelCrud extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-crud';
    }
}
