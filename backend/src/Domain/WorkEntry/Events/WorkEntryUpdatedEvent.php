<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Events;

use DateTimeImmutable;

/**
 * Event representing an update to a work entry.
 */
class WorkEntryUpdatedEvent
{
    /**
     * Constructor for WorkEntryUpdatedEvent.
     * 
     * @param string $userId The ID of the user associated with the event.
     * @param string $workEntryId The ID of the work entry that was updated.
     * @param DateTimeImmutable $startDate The new start date of the work entry.
     * @param DateTimeImmutable $endDate The new end date of the work entry.
     */
    public function __construct(
        private readonly string $userId,
        private readonly string $workEntryId,
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
    ) {}

    /**
     * Get the user ID associated with the event.
     * 
     * @return string The user ID.
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * Get the work entry ID associated with the event.
     * 
     * @return string The work entry ID.
     */
    public function getWorkEntryId(): string
    {
        return $this->workEntryId;
    }

    /**
     * Get the start date of the work entry.
     * 
     * @return DateTimeImmutable The start date.
     */
    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * Get the end date of the work entry.
     * 
     * @return DateTimeImmutable The end date.
     */
    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }
}