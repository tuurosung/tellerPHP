<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Exceptions;

use RuntimeException;
use Tuurosung\Teller\Exceptions\TellerExceptionInterface;

class TellerException extends RuntimeException implements TellerExceptionInterface
{
}