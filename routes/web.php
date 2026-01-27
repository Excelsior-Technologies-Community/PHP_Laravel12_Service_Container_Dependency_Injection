<?php

use App\Http\Controllers\GreetingController;

Route::get('/greet', [GreetingController::class, 'index']);
