<?php

namespace App\Services\Benchmark;

class TransientReportService
{
    protected int $instanceId;
    protected string $uuid;

    public function __construct()
    {
        $this->instanceId = spl_object_id($this);
        $this->uuid = uniqid('transient_', true);
    }

    public function getInstanceId(): int
    {
        return $this->instanceId;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
