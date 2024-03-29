<?php

namespace Tychovbh\LaravelCrud\Tests\App\Routes;

use Illuminate\Support\Facades\Route;
use Tychovbh\LaravelCrud\Controller;
use Tychovbh\LaravelCrud\Routes\Routes;
use Tychovbh\LaravelCrud\Tests\App\Http\Resources\V1UserResource;
use Tychovbh\LaravelCrud\Tests\App\Models\User;

class UserRoute implements Routes
{
    /**
     * Load User Routes.
     */
    public static function routes()
    {
        Route::get('/users', [Controller::class, 'index'])->name('users.index');
        Route::get('/v1/users', [Controller::class, 'index'])
            ->name('users.v1.index')
            ->resource(V1UserResource::class);
        Route::get('/users/count', [Controller::class, 'count'])->name('users.count');
        Route::get('/users/{user}', [Controller::class, 'show'])->name('users.show')
            ->middleware(['auth', 'cache'])
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
