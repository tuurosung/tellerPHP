<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Status;


/**
 * TheTeller answers with a three character code. Only a small number carry
 * control-flow meaning for us; everything else is a decline with a reason
 * string attached. Verify this list against current PaySwitch documentation
 * before you go live, since codes are added over time.
 */
final class ResponseCode
{
    public const APPROVED = '000';
    public const DUPLICATE_TRANSACTION_ID = '104';
    public const PENDING = ['111', '112', '114'];


    public static function isApproved(string $code): bool
    {
        return $code === self::APPROVED;
    }


    public static function isPending(string $code): bool
    {
        return $code === self::PENDING;
    }


    public static function isDuplicate(string $code): bool
    {
        return $code === self::DUPLICATE_TRANSACTION_ID;
    }
}