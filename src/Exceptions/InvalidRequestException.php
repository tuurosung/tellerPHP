<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Exceptions;

use InvalidArgumentException;
use Tuurosung\Teller\Exceptions\TellerExceptionInterface;

final class InvalidRequestException extends InvalidArgumentException implements TellerExceptionInterface
{
    public static function badReference(string $given): self
    {
        return new self(
            sprintf(
                'transaction_id must be exactly 12 digits, got "%s". Use Reference::generate() or supply your own.',
                $given
            )
        );
    }


    public static function badAmount(int $pesewas): self
    {
        return new self(
            sprintf(
                'Amount must be a positive integer in pesewas below 1,000,000,000, got %d',
                $pesewas
                )
        );
    }


    public static function shortDescription(int $length): self
    {
        return new self(
            sprintf(
                'TheTeller requires a description of at least 10 characters, got %d.',
                $length
            )
        );
    }


    public static function badUrl(string $field, string $value): self
    {
        return new self(
            sprintf(
                '%s must be an absolute http(s) URL, got "%s".',
                $field,
                $value
            )
        );
    }


    public static function badEmail(string $value): self
    {
        return new self(
            sprintf(
                'Email address must be valid, got "%s".',
                $value
            )
        );
    }
}