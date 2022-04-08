<?php

namespace Tychovbh\LaravelCrud\Tests\App\Routes;

use Illuminate\Support\Facades\Route;
use Tychovbh\LaravelCrud\Controller;
use Tychovbh\LaravelCrud\Routes\Routes;

class PostRoute implements Routes
{
    /**
     * Load Post Routes.
     */
    public static function routes()
    {
        Route::get('/posts', [Controller::class, 'index'])->name('posts.index');
        Route::get('/posts/{model}', [Controller::class, 'show'])->name('posts.show');
        Route::post('/posts', [Controller::class, 'store'])->name('posts.store');
        Route::put('/posts/{model}', [Controller::class, 'update'])->name('posts.update');
    }
}
