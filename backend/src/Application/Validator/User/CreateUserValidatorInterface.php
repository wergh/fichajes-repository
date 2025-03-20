<?php

namespace App\Application\Validator\User;

use App\Application\Command\User\CreateUserCommand;

/**
 * Interface for validating user creation commands.
 */
interface CreateUserValidatorInterface
{
    /**
     * Validate the given CreateUserCommand.
     * 
     * @param CreateUserCommand $command The command to validate.
     */
    public function validate(CreateUserCommand $command): void;
}