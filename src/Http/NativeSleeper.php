<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Http;

use Tuurosung\Teller\Contracts\SleeperContract;


final class NativeSleeper implements SleeperContract
{
    public function sleepMs(int $milliseconds): void
    {
        if ($milliseconds > 0) {
            usleep($milliseconds * 1000);
        }
    }
}