<?php

namespace RateLimiter\RateLimiter;

class RateLimiterConfig
{
    public function __construct(
        private mixed $maxAttempts,
        private mixed $retryIntervalInSeconds,
        private mixed $blockDurationInSeconds
    ) {}

    public function getMaxAttempts(): int
    {
        return intval($this->maxAttempts);
    }

    public function getRetryInterval(): int
    {
        return intval($this->retryIntervalInSeconds);
    }

    public function getBlockDuration(): int
    {
        return intval($this->blockDurationInSeconds);
    }
}
