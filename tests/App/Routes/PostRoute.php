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
        Route::get('/posts/{post}', [Controller::class, 'show'])->name('posts.show');
        Route::post('/posts', [Controller::class, 'store'])->name('posts.store');
        Route::put('/posts/{post}', [Controller::class, 'update'])->name('posts.update');
        Route::put('/posts/{post}/restore', [Controller::class, 'restore'])->name('posts.restore');
        Route::delete('/posts/{post}', [Controller::class, 'destroy'])->name('posts.destroy');
        Route::delete('/posts/{post}/force', [Controller::class, 'forceDestroy'])->name('posts.forceDestroy');
    }
}
