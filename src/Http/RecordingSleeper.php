<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Http;

use Tuurosung\Teller\Contracts\SleeperContract;


final class RecordingSleeper implements SleeperContract
{
    public array $slept = [];

   
    public function sleepMs(int $milliseconds): void
    {
       $this->slept[] = $milliseconds;
    }
}
