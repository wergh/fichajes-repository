<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Exception thrown when attempting to open a work entry that is already open.
 */
final class WorkEntryAlreadyOpenException extends DomainException
{
}