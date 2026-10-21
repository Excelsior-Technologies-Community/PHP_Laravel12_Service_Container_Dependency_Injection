<?php

namespace App\Contracts;

interface NotificationChannelInterface
{
    /**
     * Send notification to the given recipient.
     *
     * @return array{channel: string, recipient: string, status: string, latency_ms: float, payload: array}
     */
    public function send(string $recipient, string $message): array;

    /**
     * Get the human-readable channel name.
     */
    public function getChannelName(): string;

    /**
     * Get Bootstrap / UI icon class for this channel.
     */
    public function getIcon(): string;
}
