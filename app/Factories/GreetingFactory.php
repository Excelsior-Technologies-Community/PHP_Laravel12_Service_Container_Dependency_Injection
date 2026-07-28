<?php

namespace App\Factories;

use App\Contracts\GreetingInterface;
use App\Services\Greetings\EnglishGreetingService;
use App\Services\Greetings\FormalGreetingService;
use App\Services\Greetings\MotivationalGreetingService;

class GreetingFactory
{
    public function make(string $type): GreetingInterface
    {
        return match ($type) {

            'formal' =>
                app(FormalGreetingService::class),

            'motivational' =>
                app(MotivationalGreetingService::class),

            default =>
                app(EnglishGreetingService::class),
        };
    }
}