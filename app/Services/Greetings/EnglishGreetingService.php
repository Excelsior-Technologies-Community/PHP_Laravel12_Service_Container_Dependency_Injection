<?php

namespace App\Services\Greetings;

use App\Contracts\GreetingInterface;

class EnglishGreetingService implements GreetingInterface
{
    public function greet(): string
    {
        return "Hello 👋 Welcome to Laravel 12 Dependency Injection!";
    }
}