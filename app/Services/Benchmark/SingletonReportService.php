<?php

namespace App\Services\Benchmark;

class SingletonReportService
{
    protected int $instanceId;
    protected string $uuid;

    public function __construct()
    {
        $this->instanceId = spl_object_id($this);
        $this->uuid = uniqid('singleton_', true);
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
