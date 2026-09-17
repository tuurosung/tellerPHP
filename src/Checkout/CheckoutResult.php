<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Checkout;

final readonly class CheckoutResult
{
    private function __construct(
        public CheckoutOutcome $outcome,
        public string $transactionId,
        public ?string $checkoutUrl,
        public ?string $code,
        public ?string $reason,
        public array $raw = []
    ) {}


    /**
     * @param array<array, mixed> $raw
     */
    public static function initiated(string $transactionId, string $checkoutUrl, string $code, array $raw): self
    {
        return new self(
            CheckoutOutcome::Initiated,
            $transactionId,
            $checkoutUrl,
            $code,
            null,
            $raw
        );
    }


    public static function declined(string $transactionId, string $code, ?string $reason, array $raw = []): self
    {
        return new self(
            CheckoutOutcome::Declined,
            $transactionId,
            null,
            $code,
            $reason,
            $raw
        );
    }


    public static function indeteminate(string $transactionId, string $reason): self
    {
        return new self(
            CheckoutOutcome::Indeterminate,
            $transactionId,
            null,
            null,
            $reason
        );
    }


    public function wasInitiated(): bool
    {
        return $this->outcome === CheckoutOutcome::Initiated;
    }


    public function wasDeclined(): bool
    {
        return $this->outcome === CheckoutOutcome::Declined;
    }


     /**
     * True when the caller must resolve the outcome by querying status.
     * Treating this as a failure is how duplicate charges happen.
     */
    public function requiresResolution(): bool
    {
        return $this->outcome === CheckoutOutcome::Indeterminate;
    }

}