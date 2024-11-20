<?php

namespace RateLimiter\RateLimiter\Exception;

use Exception;

class RateLimitExceededException extends Exception
{
    public function __construct(string $message = "Rate limit exceeded")
    {
        parent::__construct($message);
    }
}
