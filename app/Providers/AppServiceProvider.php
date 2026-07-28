<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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

        /*
        |--------------------------------------------------------------------------
        | Greeting Service Binding
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            GreetingInterface::class,
            EnglishGreetingService::class
        );


        /*
        |--------------------------------------------------------------------------
        | Greeting Theme Binding
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            GreetingThemeInterface::class,
            ProfessionalThemeService::class
        );


        /*
        |--------------------------------------------------------------------------
        | Greeting Factory Singleton
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(
            GreetingFactory::class,
            function () {

                return new GreetingFactory();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Greeting Theme Factory Singleton
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(
            GreetingThemeFactory::class,
            function () {

                return new GreetingThemeFactory();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Greeting Logger Singleton
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(
            GreetingLoggerService::class,
            function () {

                return new GreetingLoggerService();

            }
        );

    }


    public function boot(): void
    {

    }

}