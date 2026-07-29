<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use App\Contracts\GreetingInterface;
use App\Contracts\GreetingThemeInterface;
use App\Services\Greetings\EnglishGreetingService;
use App\Services\Themes\ProfessionalThemeService;
use App\Factories\GreetingFactory;
use App\Factories\GreetingThemeFactory;
use App\Services\GreetingLoggerService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            GreetingInterface::class,
            EnglishGreetingService::class
        );

        $this->app->bind(
            GreetingThemeInterface::class,
            ProfessionalThemeService::class
        );

        $this->app->singleton(
            GreetingFactory::class,
            fn() => new GreetingFactory()
        );

        $this->app->singleton(
            GreetingThemeFactory::class,
            fn() => new GreetingThemeFactory()
        );

        $this->app->singleton(
            GreetingLoggerService::class,
            fn() => new GreetingLoggerService()
        );
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
