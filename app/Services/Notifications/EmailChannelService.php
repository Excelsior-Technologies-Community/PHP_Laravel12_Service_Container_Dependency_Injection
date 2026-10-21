<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationChannelInterface;

class EmailChannelService implements NotificationChannelInterface
{
    public function send(string $recipient, string $message): array
    {
        $start = microtime(true);
        // Simulate SMTP delivery overhead
        usleep(rand(15000, 35000));
        $latencyMs = round((microtime(true) - $start) * 1000, 2);

        return [
            'channel' => 'Email',
            'channel_key' => 'email',
            'icon' => 'bi-envelope-at-fill',
            'color' => 'primary',
            'recipient' => str_contains($recipient, '@') ? $recipient : "{$recipient}@example.com",
            'status' => 'Delivered (SMTP 250 OK)',
            'latency_ms' => $latencyMs,
            'details' => [
                'smtp_server' => 'smtp.mailgun.org:587',
                'message_id' => '<' . bin2hex(random_bytes(8)) . '@mailgun.org>',
                'subject' => 'System Notification: ' . \Illuminate\Support\Str::limit($message, 25),
                'content_type' => 'text/html; charset=UTF-8',
            ],
        ];
    }

    public function getChannelName(): string
    {
        return 'Email (SMTP / Mailgun)';
    }

    public function getIcon(): string
    {
        return 'bi-envelope-at-fill';
    }
}
