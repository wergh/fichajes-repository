<?php

declare(strict_types=1);

namespace App\Application\Validator\WorkEntry;

use App\Application\Command\WorkEntry\UpdateWorkEntryCommand;

/**
 * Interface for validating work entry update commands.
 */
interface UpdateWorkEntryValidatorInterface
{
    /**
     * Validate the given UpdateWorkEntryCommand.
     * 
     * @param UpdateWorkEntryCommand $command The command to validate.
     */
    public function validate(UpdateWorkEntryCommand $command): void;
}