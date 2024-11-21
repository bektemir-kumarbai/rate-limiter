<?php
namespace RateLimiter\RateLimiter;

use RateLimiter\RateLimiter\Exception\RateLimitExceededException;
use Redis;

class RateLimiter
{
    private Redis $redis;
    private RateLimiterConfig $config;

    public function __construct(Redis $redis, RateLimiterConfig $config)
    {
        $this->redis = $redis;
        $this->config = $config;
    }

    /**
     *
     * @param string $key Уникальный ключ
     * @return void
     * @throws RateLimitExceededException Если лимит превышен
     */
    public function checkRateLimit(string $key): void
    {
        $attemptsKey = $key . ':attempts';
        $blockKey = $key . ':blocked';
        $lastAttemptKey = $key . ':last_attempt_time';


        if ($this->redis->exists($blockKey)) {
            $remainingTime = $this->redis->ttl($blockKey);
            throw new RateLimitExceededException("You are blocked. Try again in $remainingTime seconds.");
        }

        $lastAttemptTime = (int)$this->redis->get($lastAttemptKey);
        $currentTime = time();

        if ($lastAttemptTime > 0 && ($currentTime - $lastAttemptTime) < $this->config->getRetryInterval()) {
            $remainingTime = $this->config->getRetryInterval() - ($currentTime - $lastAttemptTime);
            throw new RateLimitExceededException("Too many attempts. Try again in $remainingTime seconds.");
        }

        $attempts = (int)$this->redis->get($attemptsKey) ?: 0;

        if ($attempts >= $this->config->getMaxAttempts()) {
            $this->redis->set($blockKey, 1, $this->config->getBlockDuration());
            $this->redis->del($attemptsKey);
            $remainingTime = $this->redis->ttl($blockKey);
            throw new RateLimitExceededException("Too many attempts. Try again in $remainingTime seconds.");
        }

        $this->redis->incr($attemptsKey);


        $this->redis->set($lastAttemptKey, $currentTime);


        if ($this->redis->ttl($attemptsKey) === -1) {
            $this->redis->expire($attemptsKey, $this->config->getRetryInterval());
        }
    }
}


