<?php

namespace App\Services\Greetings;

use App\Contracts\GreetingInterface;

class FormalGreetingService implements GreetingInterface
{
    public function greet(): string
    {
        return "Good Day! Welcome to our Laravel 12 Application.";
    }
}