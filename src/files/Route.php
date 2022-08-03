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
        Route::get('/{models}/count', [Controller::class, 'count'])->name('{models}.count');
        Route::get('/{models}/{{model}}', [Controller::class, 'show'])->name('{models}.show');
        Route::post('/{models}', [Controller::class, 'store'])->name('{models}.store');
        Route::put('/{models}/{{model}}', [Controller::class, 'update'])->name('{models}.update');
        Route::put('/{models}/{{model}}/restore', [Controller::class, 'restore'])->name('{models}.restore');
        Route::delete('/{models}/{{model}}', [Controller::class, 'destroy'])->name('{models}.destroy');
        Route::delete('/{models}/{{model}}/force', [Controller::class, 'forceDestroy'])->name('{models}.forceDestroy');
    }
}
