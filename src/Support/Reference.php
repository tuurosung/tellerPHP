<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Support;

use Dom\RandomException;
use Tuurosung\Teller\Exceptions\InvalidRequestException;

final class Reference
{
    private const COUNTER_SPACE = 1_000_000;

    private static ?int $counter = 0;


    public static function generate(): string
    {
        self::$counter = self::$counter === null
            ? self::seed()
            : (self::$counter + 1) % self::COUNTER_SPACE;

        return self::pad(time() % 1_000_000).self::pad(self::$counter);
    }


    /**
     * The correct primitive for high volume: hand in a monotonically
     * increasing value your database owns.
     */
    public static function fromSequence(int $sequence): string
    {
        if ($sequence < 0 || $sequence >= self::COUNTER_SPACE) {
            throw InvalidRequestException::badReference((string) $sequence);
        }

        return self::pad(time() % 1_000_000).self::pad($sequence);
    }


    public static function isValid(string $reference): bool
    {
        return preg_match('/^\d{12}$/', $reference) === 1;
    }


    public static function assetValid(string $reference): string
    {
        if (! self::isValid($reference)) {
            throw InvalidRequestException::badReference($reference);
        }

        return $reference;
    }


    private static function seed(): int
    {
        try {
            return random_int(0, self::COUNTER_SPACE - 1);
        } catch (RandomException) {
            return (int) substr((string) hrtime(true), - 6);
        }
    }

    private static function pad(int $value): string
    {
        return str_pad((string) $value, 6, '0', STR_PAD_LEFT);
    }
}