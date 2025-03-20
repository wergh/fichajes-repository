<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\UserId;
use App\Domain\WorkEntry\Entity\WorkEntry;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use App\Domain\WorkEntry\Entity\WorkEntryLog;

/**
 * User entity class
 */
class User
{

    private UserId $id;

    private string $name;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    private ?DateTimeImmutable $deletedAt;

    private Collection $workEntries;

    private Collection $updatedWorkEntryLogs;

    /**
     * User constructor
     * 
     * @param UserId $id The user identifier
     * @param string $name The user name
     */
    public function __construct(UserId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->workEntries = new ArrayCollection();
        $this->updatedWorkEntryLogs = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Get the user identifier
     * 
     * @return UserId The user identifier
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * Get the user name
     * 
     * @return string The user name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the user name
     * 
     * @param string $name The new user name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the creation date
     * 
     * @return DateTimeImmutable The creation date
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the last update date
     * 
     * @return DateTimeImmutable The last update date
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Get the deletion date
     * 
     * @return DateTimeImmutable|null The deletion date or null if not deleted
     */
    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * Mark the user as deleted
     * 
     * @return self
     */
    public function delete(): self
    {
        $this->deletedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Check if the user is deleted
     * 
     * @return bool True if the user is deleted, false otherwise
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * Mark the user as updated
     */
    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Get the user's work entries
     * 
     * @param bool $withDeleted Whether to include deleted entries
     * @return Collection The collection of work entries
     */
    public function getWorkEntries(bool $withDeleted = false): Collection
    {
        $criteria = Criteria::create();

        if (!$withDeleted) {
            $criteria->where(Criteria::expr()->isNull('deletedAt'));
        }

        return $this->workEntries->matching($criteria);
    }

    /**
     * Add a work entry to the user
     * 
     * @param WorkEntry $workEntry The work entry to add
     * @return self
     */
    public function addWorkEntry(WorkEntry $workEntry): self
    {
        foreach ($this->workEntries as $entry) {
            if ($entry->getId()->equals($workEntry->getId())) {
                return $this;
            }
        }

        $this->workEntries->add($workEntry);
        return $this;
    }

    /**
     * Get the updated work entry logs
     * 
     * @return Collection The collection of updated work entry logs
     */
    public function getUpdatedWorkEntryLogs(): Collection
    {
        return $this->updatedWorkEntryLogs;
    }
}