<?php

declare(strict_types=1);

namespace App\Application\Command\WorkEntry;

use DateTimeImmutable;

/**
 * Command for updating an existing work entry.
 */
class UpdateWorkEntryCommand
{
    /**
     * Constructor for UpdateWorkEntryCommand.
     * 
     * @param string $userId The ID of the user associated with the work entry.
     * @param string $workEntryId The ID of the work entry to update.
     * @param DateTimeImmutable $startDate The new start date for the work entry.
     * @param DateTimeImmutable $endDate The new end date for the work entry.
     */
    public function __construct(
        private readonly string $userId,
        private readonly string $workEntryId,
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
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

    /**
     * Get the work entry ID to update.
     * 
     * @return string The work entry ID.
     */
    public function getWorkEntryId(): string
    {
        return $this->workEntryId;
    }

    /**
     * Get the new start date for the work entry.
     * 
     * @return DateTimeImmutable The start date.
     */
    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * Get the new end date for the work entry.
     * 
     * @return DateTimeImmutable The end date.
     */
    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }
}