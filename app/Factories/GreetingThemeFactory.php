<?php

namespace App\Factories;

use App\Contracts\GreetingThemeInterface;
use App\Services\Themes\FunThemeService;
use App\Services\Themes\ProfessionalThemeService;

class GreetingThemeFactory
{

    public function make(string $theme): GreetingThemeInterface
    {

        return match($theme){

            'fun' =>
                app(FunThemeService::class),

            default =>
                app(ProfessionalThemeService::class),

        };

    }

}