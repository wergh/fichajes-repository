<?php

namespace App\Domain\WorkEntry\Entity;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\EndDateInTheFutureNotAllowed;
use App\Domain\WorkEntry\Events\WorkEntryUpdatedEvent;
use App\Domain\WorkEntry\Exception\NotOverlapException;
use App\Domain\WorkEntry\ValueObject\WorkEntryId;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Entity representing a work entry.
 */
class WorkEntry
{
    private WorkEntryId $id;
    private User $user;
    private DateTimeImmutable $startDate;
    private ?DateTimeImmutable $endDate = null;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private ?DateTimeImmutable $deletedAt = null;

    private Collection $logs;

    private array $domainEvents = [];

    /**
     * Constructor for WorkEntry.
     * 
     * @param WorkEntryId $id The work entry ID.
     * @param User $user The user associated with the work entry.
     * @param DateTimeImmutable $startDate The start date of the work entry.
     */
    public function __construct(
        WorkEntryId       $id,
        User              $user,
        DateTimeImmutable $startDate
    )
    {
        $this->id = $id;
        $this->setUser($user);
        $this->startDate = $startDate;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->logs = new ArrayCollection();
    }

    /**
     * Get the work entry ID.
     * 
     * @return WorkEntryId The work entry ID.
     */
    public function getId(): WorkEntryId
    {
        return $this->id;
    }

    /**
     * Get the creation date.
     * 
     * @return DateTimeImmutable The creation date.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the last update date.
     * 
     * @return DateTimeImmutable The last update date.
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Get the deletion date.
     * 
     * @return DateTimeImmutable|null The deletion date or null if not deleted.
     */
    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * Mark the work entry as deleted.
     * 
     * @return self
     */
    public function delete(): self
    {
        $this->deletedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Check if the work entry is deleted.
     * 
     * @return bool True if deleted, false otherwise.
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * Mark the work entry as updated.
     */
    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Get the user associated with the work entry.
     * 
     * @return User|null The user or null if not set.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user associated with the work entry.
     * 
     * @param User|null $user The user to set.
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * Check if the work entry is open.
     * 
     * @return bool True if open, false otherwise.
     */
    public function isOpen(): bool
    {
        return $this->endDate === null;
    }

    /**
     * Close the work entry by setting the end date to now.
     * 
     * @return self
     */
    public function close(): self
    {
        $this->setEndDate(new DateTimeImmutable());
        return $this;
    }

    /**
     * Update the work entry details.
     * 
     * @param string $userId The user ID.
     * @param DateTimeImmutable $startDate The new start date.
     * @param DateTimeImmutable $endDate The new end date.
     * @param WorkEntry|null $previousEntry The previous work entry.
     * @param WorkEntry|null $nextEntry The next work entry.
     * @return self
     * @throws NotOverlapException
     * @throws EndDateInTheFutureNotAllowed
     */
    public function update(string $userId, DateTimeImmutable $startDate, DateTimeImmutable $endDate, ?WorkEntry $previousEntry, ?WorkEntry $nextEntry): self
    {
        if (
            $startDate->format(DATE_ATOM) === $this->getStartDate()->format(DATE_ATOM) &&
            $endDate->format(DATE_ATOM) === $this->getEndDate()->format(DATE_ATOM)
        ) {
            return $this;
        }

        $this->ensureEndDateIsNotInFuture($endDate);
        $this->ensureNoOverlapWithPreviousEntry($startDate, $previousEntry);
        $this->ensureNoOverlapWithNextEntry($endDate, $nextEntry);

        $this->recordDomainEvent(new WorkEntryUpdatedEvent($userId, $this->id, $this->getStartDate(), $this->getEndDate()));
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);

        return $this;
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
     * Set the start date of the work entry.
     * 
     * @param DateTimeImmutable $startDate The start date to set.
     * @return self
     */
    public function setStartDate(DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Get the end date of the work entry.
     * 
     * @return DateTimeImmutable|null The end date or null if not set.
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * Set the end date of the work entry.
     * 
     * @param DateTimeImmutable $endDate The end date to set.
     * @return self
     */
    public function setEndDate(DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Ensure the end date is not in the future.
     * 
     * @param DateTimeImmutable $endDate The end date to check.
     * @throws EndDateInTheFutureNotAllowed
     */
    private function ensureEndDateIsNotInFuture(DateTimeImmutable $endDate): void
    {
        if ($endDate > new DateTimeImmutable()) {
            throw new EndDateInTheFutureNotAllowed('End date cannot be in the future.');
        }
    }

    /**
     * Ensure there is no overlap with the previous work entry.
     * 
     * @param DateTimeImmutable $startDate The start date to check.
     * @param WorkEntry|null $previousEntry The previous work entry.
     * @throws NotOverlapException
     */
    private function ensureNoOverlapWithPreviousEntry(DateTimeImmutable $startDate, ?WorkEntry $previousEntry): void
    {
        if ($previousEntry !== null && $startDate < $previousEntry->getEndDate()) {
            throw new NotOverlapException('Start date cannot be before the end date of the previous entry.');
        }
    }

    /**
     * Ensure there is no overlap with the next work entry.
     * 
     * @param DateTimeImmutable $endDate The end date to check.
     * @param WorkEntry|null $nextEntry The next work entry.
     * @throws NotOverlapException
     */
    private function ensureNoOverlapWithNextEntry(DateTimeImmutable $endDate, ?WorkEntry $nextEntry): void
    {
        if ($nextEntry !== null && $endDate > $nextEntry->getStartDate()) {
            throw new NotOverlapException('End date cannot be after the start date of the next entry.');
        }
    }

    /**
     * Record a domain event.
     * 
     * @param object $event The event to record.
     */
    private function recordDomainEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * Release all recorded domain events.
     * 
     * @return array The array of domain events.
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }

    /**
     * Get the logs associated with the work entry.
     * 
     * @return Collection The collection of logs.
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }
}