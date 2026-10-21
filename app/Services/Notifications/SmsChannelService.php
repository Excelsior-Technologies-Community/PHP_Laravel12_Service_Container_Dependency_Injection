<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationChannelInterface;

class SmsChannelService implements NotificationChannelInterface
{
    public function send(string $recipient, string $message): array
    {
        $start = microtime(true);
        // Simulate Twilio SMS gateway overhead
        usleep(rand(20000, 45000));
        $latencyMs = round((microtime(true) - $start) * 1000, 2);

        $phone = preg_replace('/[^0-9+]/', '', $recipient);
        if (empty($phone)) {
            $phone = '+1-800-555-' . rand(1000, 9999);
        }

        return [
            'channel' => 'SMS Gateway',
            'channel_key' => 'sms',
            'icon' => 'bi-chat-dots-fill',
            'color' => 'success',
            'recipient' => $phone,
            'status' => 'Delivered (Twilio SM' . substr(md5(uniqid()), 0, 10) . ')',
            'latency_ms' => $latencyMs,
            'details' => [
                'gateway' => 'Twilio REST API v2010',
                'segments' => ceil(strlen($message) / 160),
                'characters' => strlen($message),
                'route' => 'Direct Carrier Tier-1',
            ],
        ];
    }

    public function getChannelName(): string
    {
        return 'SMS (Twilio Gateway)';
    }

    public function getIcon(): string
    {
        return 'bi-chat-dots-fill';
    }
}
