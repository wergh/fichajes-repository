<?php

declare(strict_types=1);

namespace App\Application\Command\User;

/**
 * Command for creating a new user.
 */
class CreateUserCommand
{
    /**
     * Constructor for CreateUserCommand.
     * 
     * @param string $name The name of the user to create.
     */
    public function __construct(
        private readonly string $name,
    ) {}

    /**
     * Get the name of the user to create.
     * 
     * @return string The user's name.
     */
    public function getName(): string
    {
        return $this->name;
    }
}