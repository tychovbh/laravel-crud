<?php

namespace Tychovbh\LaravelCrud\Tests\App\Routes;

use Illuminate\Support\Facades\Route;
use Tychovbh\LaravelCrud\Controller;
use Tychovbh\LaravelCrud\Routes\Routes;

class PageRoute implements Routes
{
    /**
     * Load Page Routes.
     */
    public static function routes()
    {
        Route::get('/pages', [Controller::class, 'index'])->name('pages.index');
        Route::get('/pages/{id}', [Controller::class, 'show'])->name('pages.show');
        Route::post('/pages', [Controller::class, 'store'])->name('pages.store');
        Route::put('/pages/{id}', [Controller::class, 'update'])->name('pages.update');
    }
}
