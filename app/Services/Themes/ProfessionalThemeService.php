<?php

namespace App\Services\Themes;

use App\Contracts\GreetingThemeInterface;

class ProfessionalThemeService implements GreetingThemeInterface
{
    public function format(string $message): string
    {
        return "[PROFESSIONAL]\n\n" . $message;
    }
}