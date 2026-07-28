<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GreetingLoggerService
{
    public function log(
        string $type,
        string $message
    ): void {

        Log::channel('daily')->info(
            'Greeting Generated',
            [
                'type' => $type,
                'message' => $message,
                'ip' => request()->ip(),
                'browser' => request()->userAgent(),
            ]
        );
    }
}