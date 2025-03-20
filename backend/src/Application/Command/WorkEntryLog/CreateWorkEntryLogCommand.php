<?php

declare(strict_types=1);

namespace App\Application\Command\WorkEntryLog;

use DateTimeImmutable;

/**
 * Command for creating a new work entry log.
 */
class CreateWorkEntryLogCommand
{
    /**
     * Constructor for CreateWorkEntryLogCommand.
     * 
     * @param string $workEntryId The ID of the work entry associated with the log.
     * @param string $updatedById The ID of the user who updated the log.
     * @param DateTimeImmutable $startTime The start time of the log entry.
     * @param DateTimeImmutable $endTime The end time of the log entry.
     */
    public function __construct(
        private readonly string $workEntryId,
        private readonly string $updatedById,
        private readonly DateTimeImmutable $startTime,
        private readonly DateTimeImmutable $endTime,
    ) {}

    /**
     * Get the work entry ID associated with the log.
     * 
     * @return string The work entry ID.
     */
    public function getWorkEntryId(): string
    {
        return $this->workEntryId;
    }

    /**
     * Get the user ID who updated the log.
     * 
     * @return string The user ID.
     */
    public function getUpdatedById(): string
    {
        return $this->updatedById;
    }

    /**
     * Get the start time of the log entry.
     * 
     * @return DateTimeImmutable The start time.
     */
    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * Get the end time of the log entry.
     * 
     * @return DateTimeImmutable The end time.
     */
    public function getEndTime(): DateTimeImmutable
    {
        return $this->endTime;
    }
}