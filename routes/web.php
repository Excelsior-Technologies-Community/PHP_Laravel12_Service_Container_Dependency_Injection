<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetingController;
use App\Http\Controllers\ContainerVisualizerController;
use App\Http\Controllers\ContainerBroadcastController;
use App\Http\Controllers\ContainerBenchmarkController;
use App\Http\Controllers\ContainerPipelineController;

/*
|--------------------------------------------------------------------------
| Web Routes - Laravel 12 Service Container & Dependency Injection
|--------------------------------------------------------------------------
*/

// Welcome / Default Landing
Route::get('/', function () {
    return redirect('/greet');
});

// Original Greeting Dashboard
Route::get('/greet', [GreetingController::class, 'index'])->name('greet.index');
Route::get('/greet/export', [GreetingController::class, 'export'])->name('greet.export');
Route::delete('/greet/history/{history}', [GreetingController::class, 'destroy'])->name('greet.destroy');
Route::delete('/greet/history', [GreetingController::class, 'destroyAll'])->name('greet.destroyAll');

// Feature 1: Interactive Service Container Visualizer & Diagnostics Studio
Route::prefix('container/visualizer')->name('container.visualizer.')->group(function () {
    Route::get('/', [ContainerVisualizerController::class, 'index'])->name('index');
    Route::post('/resolve', [ContainerVisualizerController::class, 'resolve'])->name('resolve');
});

// Feature 2: Tagged Services & Multi-Channel Broadcast Engine ($app->tag)
Route::prefix('container/broadcast')->name('container.broadcast.')->group(function () {
    Route::get('/', [ContainerBroadcastController::class, 'index'])->name('index');
    Route::post('/send', [ContainerBroadcastController::class, 'send'])->name('send');
});

// Feature 3: Container Lifecycle & Performance Benchmark Lab
Route::prefix('container/benchmark')->name('container.benchmark.')->group(function () {
    Route::get('/', [ContainerBenchmarkController::class, 'index'])->name('index');
    Route::post('/run', [ContainerBenchmarkController::class, 'run'])->name('run');
});

// Feature 4: Callable Pipelines & Middleware Resolver (Pipeline::send)
Route::prefix('container/pipeline')->name('container.pipeline.')->group(function () {
    Route::get('/', [ContainerPipelineController::class, 'index'])->name('index');
    Route::post('/run', [ContainerPipelineController::class, 'run'])->name('run');
});
