<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationChannelInterface;

class MultiChannelBroadcastManager
{
    /**
     * Injected with tagged services: app()->tagged('notification_channels')
     *
     * @param iterable<NotificationChannelInterface> $channels
     */
    public function __construct(protected iterable $channels)
    {
    }

    /**
     * Broadcast message to all active or selected tagged notification channels.
     */
    public function broadcast(string $recipient, string $message, array $selectedKeys = []): array
    {
        $startTime = microtime(true);
        $results = [];

        foreach ($this->channels as $channel) {
            if (!$channel instanceof NotificationChannelInterface) {
                continue;
            }

            // If specific keys selected, skip unselected
            $channelResult = $channel->send($recipient, $message);
            if (!empty($selectedKeys) && !in_array($channelResult['channel_key'], $selectedKeys)) {
                continue;
            }

            $results[] = $channelResult;
        }

        $totalTimeMs = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'total_channels' => count($results),
            'total_time_ms' => $totalTimeMs,
            'results' => $results,
        ];
    }

    /**
     * Get all registered tagged channels metadata.
     */
    public function getRegisteredChannels(): array
    {
        $channelsList = [];
        foreach ($this->channels as $channel) {
            if ($channel instanceof NotificationChannelInterface) {
                $channelsList[] = [
                    'class' => get_class($channel),
                    'name' => $channel->getChannelName(),
                    'icon' => $channel->getIcon(),
                    'object_id' => spl_object_id($channel),
                ];
            }
        }
        return $channelsList;
    }
}
