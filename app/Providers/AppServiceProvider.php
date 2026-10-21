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

// Tagged Notification Channel Services
use App\Services\Notifications\EmailChannelService;
use App\Services\Notifications\SmsChannelService;
use App\Services\Notifications\SlackChannelService;
use App\Services\Notifications\WebhookChannelService;
use App\Services\Notifications\MultiChannelBroadcastManager;

// Lifecycle Benchmark Services
use App\Services\Benchmark\TransientReportService;
use App\Services\Benchmark\SingletonReportService;
use App\Services\Benchmark\ScopedReportService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 1. Basic Interface & Theme Bindings
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

        // 2. Feature 3: Service Container Tagging ($app->tag)
        $this->app->tag([
            EmailChannelService::class,
            SmsChannelService::class,
            SlackChannelService::class,
            WebhookChannelService::class,
        ], 'notification_channels');

        // MultiChannelBroadcastManager receives tagged services iterable
        $this->app->bind(
            MultiChannelBroadcastManager::class,
            function ($app) {
                return new MultiChannelBroadcastManager(
                    $app->tagged('notification_channels')
                );
            }
        );

        // 3. Feature 4: Container Lifecycle Demonstrations
        $this->app->bind(TransientReportService::class);
        $this->app->singleton(SingletonReportService::class);
        $this->app->scoped(ScopedReportService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
