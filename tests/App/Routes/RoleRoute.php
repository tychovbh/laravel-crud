<?php

namespace Tychovbh\LaravelCrud\Tests\App\Routes;

use Illuminate\Support\Facades\Route;
use Tychovbh\LaravelCrud\Controller;
use Tychovbh\LaravelCrud\Routes\Routes;
use Tychovbh\LaravelCrud\Tests\App\Models\Role;

class RoleRoute implements Routes
{
    /**
     * Load role Routes.
     */
    public static function routes()
    {
        Route::get('/roles/{id}', [Controller::class, 'show'])->name('roles.show')
            ->middleware(['auth'])
            ->can('view', [Role::class, 'id']);
    }
}
