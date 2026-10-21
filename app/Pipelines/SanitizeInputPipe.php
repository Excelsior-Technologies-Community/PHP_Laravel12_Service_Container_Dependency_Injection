<?php

namespace App\Pipelines;

use Closure;

class SanitizeInputPipe
{
    /**
     * Sanitize and strip unsafe HTML/XSS content.
     */
    public function handle(array $payload, Closure $next)
    {
        $raw = $payload['current_text'];

        // Strip HTML tags and normalize extra whitespace
        $clean = strip_tags($raw);
        $clean = preg_replace('/\s+/', ' ', trim($clean));

        $payload['steps'][] = [
            'pipe' => 'SanitizeInputPipe',
            'pipe_class' => self::class,
            'description' => 'Strips unsafe HTML tags, script vectors, and normalizes excess whitespace.',
            'before' => $raw,
            'after' => $clean,
            'di_injected' => 'Auto-resolved via Container (zero config)',
        ];

        $payload['current_text'] = $clean;

        return $next($payload);
    }
}
