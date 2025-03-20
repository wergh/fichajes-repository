<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Exception;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Exception thrown when a work entry is not open.
 */
class NotWorkEntryOpenException extends DomainException
{

}