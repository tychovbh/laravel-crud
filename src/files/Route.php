<?php

namespace App\Routes;

use Illuminate\Support\Facades\Route;
use Tychovbh\LaravelCrud\Controller;
use Tychovbh\LaravelCrud\Routes\Routes;

class Name implements Routes
{
    /**
     * Load User Routes.
     */
    public static function routes()
    {
        Route::get('/{models}', [Controller::class, 'index'])->name('{models}.index');
        Route::get('/{models}/{id}', [Controller::class, 'show'])->name('{models}.show');
        Route::post('/{models}', [Controller::class, 'store'])->name('{models}.store');
        Route::put('/{models}/{id}', [Controller::class, 'update'])->name('{models}.update');
        Route::delete('/{models}/{id}', [Controller::class, 'destroy'])->name('{models}.destroy');
    }
}
