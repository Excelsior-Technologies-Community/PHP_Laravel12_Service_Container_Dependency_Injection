<?php

namespace App\Pipelines;

use Closure;

class ProfanityCensorPipe
{
    protected array $blocklist = ['badword', 'stupid', 'idiot', 'spam', 'hate', 'scam', 'fake'];

    public function handle(array $payload, Closure $next)
    {
        $raw = $payload['current_text'];
        $censored = $raw;

        foreach ($this->blocklist as $word) {
            $censored = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', '***', $censored);
        }

        $payload['steps'][] = [
            'pipe' => 'ProfanityCensorPipe',
            'pipe_class' => self::class,
            'description' => 'Scans dictionary against internal blocklist and censors abusive/spam tokens with asterisks (***).',
            'before' => $raw,
            'after' => $censored,
            'di_injected' => 'Blocklist Dictionary (7 terms checked)',
        ];

        $payload['current_text'] = $censored;

        return $next($payload);
    }
}
