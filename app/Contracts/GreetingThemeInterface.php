<?php

namespace App\Contracts;

interface GreetingThemeInterface
{
    public function format(string $message): string;
}