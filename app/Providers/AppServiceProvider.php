<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\GreetingInterface;
use App\Services\GreetingService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GreetingInterface::class, GreetingService::class);
    }

    public function boot(): void
    {
        //
    }
}
