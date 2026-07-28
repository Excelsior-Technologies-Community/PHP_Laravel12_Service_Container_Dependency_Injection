<?php

namespace App\Services\Themes;

use App\Contracts\GreetingThemeInterface;

class FunThemeService implements GreetingThemeInterface
{
    public function format(string $message): string
    {
        return "🚀 Hello Developer!\n\n"
            . $message
            . "\n\n🎯";
    }
}