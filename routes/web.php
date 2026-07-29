<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Laravel 12 Service Container & Dependency Injection Demo
|
*/

// Welcome Page
Route::get('/', function () {
    return redirect('/greet');
});

// Greeting Dashboard
Route::get('/greet', [GreetingController::class, 'index'])
    ->name('greet.index');

// Export Greeting History CSV
Route::get('/greet/export', [GreetingController::class, 'export'])
    ->name('greet.export');

Route::delete('/greet/history/{history}', [GreetingController::class, 'destroy'])
    ->name('greet.destroy');

Route::delete('/greet/history', [GreetingController::class, 'destroyAll'])
    ->name('greet.destroyAll');
