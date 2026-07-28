<?php

namespace App\Services\Greetings;

use App\Contracts\GreetingInterface;

class MotivationalGreetingService implements GreetingInterface
{
    public function greet(): string
    {
        return "Keep learning Laravel! Every expert was once a beginner. 🚀";
    }
}