<?php

declare(strict_types=1);

namespace App\Application\Command\WorkEntry;

/**
 * Command for creating a new work entry.
 */
class CreateWorkEntryCommand
{
    /**
     * Constructor for CreateWorkEntryCommand.
     * 
     * @param string $userId The ID of the user associated with the work entry.
     */
    public function __construct(
        private readonly string $userId,
    ) {}

    /**
     * Get the user ID associated with the work entry.
     * 
     * @return string The user ID.
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}