<?php

namespace App\Services;

use App\Contracts\GreetingInterface;

class GreetingService implements GreetingInterface
{
    public function greet(): string
    {
        return "Hello 👋 Welcome to Laravel 12 Dependency Injection!";
    }
}
