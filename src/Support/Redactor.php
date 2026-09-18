<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Support;


/**
 * Nothing that reaches a log line should carry credentials or a customer
 * identifier in full.
 */
final class Redactor
{
    private const SENSITIVE_KEYS = ['authorization', 'api_key', 'apikey', 'password', 'card_number', 'cvv', 'pin'];


    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function payload(array $payload): array
    {
        $safe = [];

        foreach ($payload as $key => $value) {
            $lower = strtolower((string) $key);

            if (in_array($lower, self::SENSITIVE_KEYS, true)) {
                $safe[$key] = ['redacted'];

                continue;
            }


            if ($lower === 'email' && is_string($value)) {
                $safe[$key] = self::email($value);

                continue;
            }

            $safe[$key] = is_array($value) ? self::payload($value) : $value;
        }

        return $safe;
    }


    public static function email(string $email): string
    {
        $at = strpos($email, '@');

        if ($at === false || $at < 1) {
            return '[redacted]';
        }

        return substr($email, 0, 1).'***'.substr($email, $at);
    }
}