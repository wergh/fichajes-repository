<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Exception thrown when an end date is set in the future, which is not allowed.
 */
class EndDateInTheFutureNotAllowed extends DomainException
{

}