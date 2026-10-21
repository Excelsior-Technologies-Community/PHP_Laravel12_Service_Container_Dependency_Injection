<?php

namespace App\Pipelines;

use Closure;

class EmojiSentimentEnricherPipe
{
    public function handle(array $payload, Closure $next)
    {
        $raw = $payload['current_text'];
        $textLower = strtolower($raw);

        $prefixes = [];
        if (str_contains($textLower, 'urgent') || str_contains($textLower, 'asap') || str_contains($textLower, 'emergency')) {
            $prefixes[] = '🚨 [URGENT]';
        }
        if (str_contains($textLower, 'great') || str_contains($textLower, 'awesome') || str_contains($textLower, 'excellent') || str_contains($textLower, 'love')) {
            $prefixes[] = '✨ [POSITIVE]';
        }
        if (str_contains($textLower, 'error') || str_contains($textLower, 'bug') || str_contains($textLower, 'issue') || str_contains($textLower, 'fail')) {
            $prefixes[] = '⚠️ [BUG-REPORT]';
        }

        $enriched = !empty($prefixes) ? implode(' ', $prefixes) . ' ' . $raw : '💬 ' . $raw;

        $payload['steps'][] = [
            'pipe' => 'EmojiSentimentEnricherPipe',
            'pipe_class' => self::class,
            'description' => 'Analyzes sentiment keywords and injects context indicators and visual tags.',
            'before' => $raw,
            'after' => $enriched,
            'di_injected' => 'Sentiment Lexicon Analyzer',
        ];

        $payload['current_text'] = $enriched;

        return $next($payload);
    }
}
