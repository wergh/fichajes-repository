<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Entity;

use App\Domain\User\Entity\User;
use DateTimeImmutable;

/**
 * Entity representing a log entry for work.
 */
class WorkEntryLog
{
    private int $id;
    private DateTimeImmutable $startTime;
    private DateTimeImmutable $endTime;
    private WorkEntry $workEntry;
    private User $updatedBy;
    private DateTimeImmutable $createdAt;

    /**
     * Constructor for WorkEntryLog.
     * 
     * @param User $user The user who updated the log.
     * @param DateTimeImmutable $startTime The start time of the log entry.
     * @param DateTimeImmutable $endTime The end time of the log entry.
     * @param WorkEntry $workEntry The work entry associated with the log.
     */
    public function __construct(
        User              $user,
        DateTimeImmutable $startTime,
        DateTimeImmutable $endTime,
        WorkEntry         $workEntry
    )
    {
        $this->setUser($user);
        $this->setWorkEntry($workEntry);
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * Set the user who updated the log.
     * 
     * @param User $user The user to set.
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->updatedBy = $user;
        return $this;
    }

    /**
     * Get the log entry ID.
     * 
     * @return int The log entry ID.
     */
    public function getId(): int
    {
        return $this->id;
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
     * Set the start time of the log entry.
     * 
     * @param DateTimeImmutable $startTime The start time to set.
     * @return self
     */
    public function setStartTime(DateTimeImmutable $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
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

    /**
     * Set the end time of the log entry.
     * 
     * @param DateTimeImmutable $endTime The end time to set.
     * @return self
     */
    public function setEndTime(DateTimeImmutable $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * Get the work entry associated with the log.
     * 
     * @return WorkEntry The work entry.
     */
    public function getWorkEntry(): WorkEntry
    {
        return $this->workEntry;
    }

    /**
     * Set the work entry associated with the log.
     * 
     * @param WorkEntry $workEntry The work entry to set.
     * @return self
     */
    public function setWorkEntry(WorkEntry $workEntry): self
    {
        $this->workEntry = $workEntry;
        return $this;
    }

    /**
     * Get the user who updated the log.
     * 
     * @return User The user.
     */
    public function getUser(): User
    {
        return $this->updatedBy;
    }

    /**
     * Get the creation date of the log entry.
     * 
     * @return DateTimeImmutable The creation date.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}