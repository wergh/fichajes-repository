<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Exception;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Exception thrown when work entries overlap.
 */
class NotOverlapException extends DomainException
{

}