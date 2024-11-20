<?php

namespace RateLimiter\RateLimiter;

class RateLimiterConfig
{
    public function __construct(
        private int $maxAttempts,
        private int $retryIntervalInSeconds,
        private int $blockDurationInSeconds
    ) {}

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function getRetryInterval(): int
    {
        return $this->retryIntervalInSeconds;
    }

    public function getBlockDuration(): int
    {
        return $this->blockDurationInSeconds;
    }
}
