<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Status;

/**
 * Pure scheduling logic for resolving an indeterminate or pending
 * transaction. Kept free of any framework so both the Laravel queue job
 * and a plain cron worker can share it.
 */
final readonly class ResolutionPolicy
{
    public function __construct(
        public int $maxAttempts = 8,
        public int $baseDelaySeconds = 15,
        public int $maxDelaySeconds = 900,
    ){}


    public static function default(): self
    {
        return new self();
    }


    public function shouldQueryAgain(int $attempt): bool
    {
        return $attempt < $this->maxAttempts;
    }


    public function delaySecondsFor(int $attempt): int
    {
        $exponential = $this->baseDelaySeconds * (2 ** max(0, $attempt - 1));
        return  (int) min($exponential, $this->maxDelaySeconds);
    }


    /**
     * Total wall-clock window before a transaction is escalated to a human.
     */
    public function windowSeconds(): int
    {
        $total = 0;

        for ($attempt = 1; $attempt < $this->maxAttempts; $attempt++) {
            $total += $this->delaySecondsFor($attempt);
        }

        return $total;
    }
}