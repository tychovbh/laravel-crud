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
        Route::get('/users/{id}', [Controller::class, 'show'])->name('users.show');
        Route::get('/users/create', [Controller::class, 'create'])->name('users.create');
        Route::post('/users', [Controller::class, 'store'])
            ->name('users.store')
            ->can('store', User::class)
            ->middleware(['validate']);
        Route::get('/users/{id}/edit', [Controller::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [Controller::class, 'update'])
            ->name('users.update')
            ->middleware(['validate']);
        Route::delete('/users/{id}', [Controller::class, 'destroy'])->name('users.destroy');
    }
}
