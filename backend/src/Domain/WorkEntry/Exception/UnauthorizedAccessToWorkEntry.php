<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Exception;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Exception thrown when there is unauthorized access to a work entry.
 */
class UnauthorizedAccessToWorkEntry extends DomainException
{

}