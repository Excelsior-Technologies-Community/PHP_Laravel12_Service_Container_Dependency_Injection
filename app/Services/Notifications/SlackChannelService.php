<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationChannelInterface;

class SlackChannelService implements NotificationChannelInterface
{
    public function send(string $recipient, string $message): array
    {
        $start = microtime(true);
        // Simulate Slack webhook latency
        usleep(rand(25000, 50000));
        $latencyMs = round((microtime(true) - $start) * 1000, 2);

        $channel = str_starts_with($recipient, '#') ? $recipient : '#alerts';

        return [
            'channel' => 'Slack',
            'channel_key' => 'slack',
            'icon' => 'bi-slack',
            'color' => 'danger',
            'recipient' => $channel,
            'status' => 'Posted to Slack Webhook (HTTP 200 OK)',
            'latency_ms' => $latencyMs,
            'details' => [
                'webhook' => 'https://hooks.slack.com/services/T00/B00/X00',
                'channel' => $channel,
                'bot_username' => 'LaravelContainerBot',
                'format' => 'mrkdwn blocks with attachments',
            ],
        ];
    }

    public function getChannelName(): string
    {
        return 'Slack (Incoming Webhook)';
    }

    public function getIcon(): string
    {
        return 'bi-slack';
    }
}
