<?php

namespace Tychovbh\LaravelCrud\Tests\App\Routes;

use Illuminate\Support\Facades\Route;
use Tychovbh\LaravelCrud\Controller;
use Tychovbh\LaravelCrud\Routes\Routes;
use Tychovbh\LaravelCrud\Tests\App\Models\User;

class UserRoute implements Routes
{
    /**
     * Load User Routes.
     */
    public static function routes()
    {
        Route::get('/users', [Controller::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [Controller::class, 'show'])->name('users.show')
            ->middleware(['auth'])
            ->can('view', [User::class, 'user']);

        Route::post('/users', [Controller::class, 'store'])
            ->name('users.store')
            ->middleware(['auth', 'validate'])
            ->can('create', User::class);
        Route::put('/users/{user}', [Controller::class, 'update'])
            ->name('users.update')
            ->middleware(['validate']);
        Route::delete('/users/{user}', [Controller::class, 'destroy'])->name('users.destroy');
    }
}
