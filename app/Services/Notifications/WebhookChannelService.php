<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationChannelInterface;

class WebhookChannelService implements NotificationChannelInterface
{
    public function send(string $recipient, string $message): array
    {
        $start = microtime(true);
        // Simulate HTTP POST webhook with HMAC signing
        usleep(rand(18000, 40000));
        $latencyMs = round((microtime(true) - $start) * 1000, 2);

        $url = filter_var($recipient, FILTER_VALIDATE_URL) ? $recipient : 'https://api.partner.io/v1/events/webhook';

        $signature = hash_hmac('sha256', $message, 'laravel_di_secret_key');

        return [
            'channel' => 'Custom Webhook',
            'channel_key' => 'webhook',
            'icon' => 'bi-hdd-network-fill',
            'color' => 'dark',
            'recipient' => $url,
            'status' => 'Dispatched (HTTP 202 Accepted)',
            'latency_ms' => $latencyMs,
            'details' => [
                'target_url' => $url,
                'x_signature' => "sha256={$signature}",
                'retry_policy' => 'Exponential backoff (3 attempts)',
                'payload_bytes' => strlen($message),
            ],
        ];
    }

    public function getChannelName(): string
    {
        return 'Custom Webhook (HMAC-SHA256)';
    }

    public function getIcon(): string
    {
        return 'bi-hdd-network-fill';
    }
}
