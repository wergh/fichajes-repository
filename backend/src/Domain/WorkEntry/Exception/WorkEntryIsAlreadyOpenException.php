<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Exception;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Exception thrown when a work entry is already open.
 */
class WorkEntryIsAlreadyOpenException extends DomainException
{

}