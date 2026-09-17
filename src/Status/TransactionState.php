<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Status;


enum TransactionState: string
{
    case Successful = 'successful';
    case Failed = 'failed';
    case Pending = 'pending';
    case Unknown = 'unknown';


    public function isTerminal(): bool
    {
        return $this === self::Successful || $this === self::Failed;
    }


    public function label(): string
    {
        return match($this) {
            self::Successful => 'Successful',
            self::Failed => 'Failed',
            self::Pending => 'Pending at the switch',
            self::Unknown => 'Unresolved'
        };
    }
}