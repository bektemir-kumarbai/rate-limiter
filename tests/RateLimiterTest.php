<?php
namespace MyVendor\RateLimiter\Tests;

use RateLimiter\RateLimiter\RateLimiter;
use RateLimiter\RateLimiter\RateLimiterConfig;
use RateLimiter\RateLimiter\Exception\RateLimitExceededException;
use PHPUnit\Framework\TestCase;
use Redis;

class RateLimiterTest extends TestCase
{
    public function testRateLimitExceeded()
    {
        $redis = $this->createMock(Redis::class);
        $rateLimiter = new RateLimiter($redis);
        $config = new RateLimiterConfig(3, 60, 3600);

        $redis->method('get')->willReturn(3);

        $this->expectException(RateLimitExceededException::class);
        $rateLimiter->checkRateLimit('test-key', $config);
    }
}
