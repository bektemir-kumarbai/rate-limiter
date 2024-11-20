<?php

namespace RateLimiter\RateLimiter;

use RateLimiter\RateLimiter\Exception\RateLimitExceededException;
use Redis;

class RateLimiter
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $key
     * @param RateLimiterConfig $config
     * @return void
     * @throws RateLimitExceededException
     */
    public function checkRateLimit(string $key, RateLimiterConfig $config): void
    {
        $attemptsKey = $key . ':attempts';
        $blockKey = $key . ':blocked';

        // Проверка блокировки
        if ($this->redis->exists($blockKey)) {
            throw new RateLimitExceededException("Too many attempts. Try again later.");
        }

        // Получение количества попыток
        $attempts = $this->redis->get($attemptsKey) ?: 0;

        if ($attempts >= $config->getMaxAttempts()) {
            // Установить блокировку
            $this->redis->set($blockKey, 1, $config->getBlockDuration());
            $this->redis->del($attemptsKey);
            throw new RateLimitExceededException("Too many attempts. Try again later.");
        }

        // Увеличение счётчика попыток
        $this->redis->incr($attemptsKey);

        // Установить TTL для ключа попыток
        if ($this->redis->ttl($attemptsKey) === -1) {
            $this->redis->expire($attemptsKey, $config->getRetryInterval());
        }
    }
}
