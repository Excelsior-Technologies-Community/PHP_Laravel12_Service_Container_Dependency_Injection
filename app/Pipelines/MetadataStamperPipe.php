<?php

namespace App\Pipelines;

use Closure;
use Illuminate\Http\Request;

class MetadataStamperPipe
{
    /**
     * Dependency injected Request and Container timestamp
     */
    public function __construct(protected Request $request)
    {
    }

    public function handle(array $payload, Closure $next)
    {
        $raw = $payload['current_text'];

        $executionStamp = [
            'stamped_at' => now()->toIso8601String(),
            'request_ip' => $this->request->ip() ?: '127.0.0.1',
            'user_agent' => substr($this->request->userAgent() ?: 'CLI/Browser', 0, 45),
            'length' => strlen($raw),
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ];

        $payload['metadata'] = $executionStamp;

        $payload['steps'][] = [
            'pipe' => 'MetadataStamperPipe',
            'pipe_class' => self::class,
            'description' => 'Injects Illuminate\Http\Request via DI constructor and appends audit traceability metadata & UUID.',
            'before' => $raw,
            'after' => $raw . " (UUID: {$executionStamp['uuid']})",
            'di_injected' => 'Illuminate\Http\Request ($request->ip(), $request->userAgent())',
        ];

        return $next($payload);
    }
}
