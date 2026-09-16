<?php

declare(strict_types= 1);

namespace Tuurosung\Teller\Support;

use Tuurosung\Teller\Exceptions\InvalidRequestException;

/**
 * Money is carried as an integer number of pesewas and only becomes a
 * zero-padded 12 digit string at the wire boundary. Floats never enter.
 */
final readonly class Amount
{
    public const MAX_PESEWAS = 999_999_999_999;

    private function __construct(
        public int $pesewas
    ){}


    public static function fromPesewas(int $pesewas): self
    {
        if ($pesewas <= 0 || $pesewas > self::MAX_PESEWAS) {
            throw InvalidRequestException::badAmount($pesewas);
        }

        return new self($pesewas);
    }


    /**
     * Parse a major-unit decimal string such as "12.50". Strings only, so that
     * no caller can hand in a float and lose a pesewa to binary rounding.
     */
    public static function fromCedis(string $cedis): self
    {
        $trimmed = trim($cedis);

        if (preg_match('/^\d{1,10}(\.\d{1,2})?$/', $trimmed) !== 1) {
            throw new InvalidRequestException(
                sprintf('Amount "%s" is not a valid cedi value. Expected forms: "12", "12.5", "12.50".', $cedis)
            );
        }

        [$whole, $fraction] = array_pad(explode('.', $trimmed, 2), 2, '0');

        return self::fromPesewas(((int) $whole * 100) + (int) str_pad($fraction, 2, '0'));
    }


    public function toWireFormat(): string
    {
        return str_pad((string) $this->pesewas, 12, '0', STR_PAD_LEFT);
    }


    public function toCedis(): string
    {
        return sprintf(
            '%d.%02d',
            intdiv($this->pesewas, 100),
            $this->pesewas % 100
        );
    }


    public function equals(self $other): bool
    {
        return $this->pesewas === $other->pesewas;
    }
}