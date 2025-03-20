<?php

declare(strict_types=1);

namespace App\Application\Validator\WorkEntry;

use App\Application\Command\WorkEntry\CreateWorkEntryCommand;

/**
 * Interface for validating work entry creation commands.
 */
interface CreateWorkEntryValidatorInterface
{
    /**
     * Validate the given CreateWorkEntryCommand.
     * 
     * @param CreateWorkEntryCommand $command The command to validate.
     */
    public function validate(CreateWorkEntryCommand $command): void;
}