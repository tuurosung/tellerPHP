<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Exceptions;

use Throwable;
use Tuurosung\Teller\Exceptions\TellerException;

final class TransportException extends TellerException
{
    public function __construct(
        string $message,
        public readonly int $attempts,
        ?Throwable $previous = null
    ){
        parent::__construct($message, 0, $previous);
    }


    public static function unreachable(string $reason, int $attempts, ?Throwable $previous = null): self
    {
        return new self(
            sprintf(
                'TheTeller API was unreachable after %d attempts. Reason: %s', $attempts, $reason
            ),
            $attempts,
            $previous
        );
    }


    public function malformedPayload(string $reason): self
    {
        return new self('Request payload could not be encoded: '. $reason,0);
    }
}