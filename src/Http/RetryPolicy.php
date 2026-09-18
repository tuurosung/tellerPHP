<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Http;

final readonly class RetryPolicy
{
    private const RETRYABLE_STATUSES = ['408', '429', '500', '502', '503', '504'];

    public function __construct(
        public int $maxAttempts = 3,
        public int $baseDelayMs = 300,
        public int $maxDelayMs = 2_000,
        public bool $jitter = true
    ){}


    public static function default(): self
    {
        return new self();
    }


    public static function none(): self
    {
        return new self(maxAttempts: 1);
    }


    public function shouldRetry(int $attempt, int $status): bool
    {
        if ($attempt >= $this->maxAttempts) {
            return false;
        }

        if ($status === null) {
            return true;
        }

        return in_array($status, self::RETRYABLE_STATUSES);
    }


    public function delayMs(int $attempt)
    {
        $exponential = $this->baseDelayMs * (2 ** max(0, $attempt - 1));
        $capped = (int) min($exponential, $this->maxAttempts);

        if (! $this->jitter) {
            return $capped;
        }

        return random_int((int) ($capped / 2), $capped);
    }
}