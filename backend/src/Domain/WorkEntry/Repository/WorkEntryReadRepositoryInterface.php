<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Repository;

use App\Domain\WorkEntry\Entity\WorkEntry;
use DateTimeImmutable;

/**
 * Interface for reading work entries from a repository.
 */
interface WorkEntryReadRepositoryInterface
{
    /**
     * Retrieve all work entries for a user.
     * 
     * @param string $userId The ID of the user.
     * @return WorkEntry[] An array of WorkEntry entities.
     */
    public function all(string $userId): array;

    /**
     * Find a work entry by its ID.
     * 
     * @param string $id The ID of the work entry.
     * @return WorkEntry|null The WorkEntry entity or null if not found.
     */
    public function findById(string $id): ?WorkEntry;

    /**
     * Find an open work entry for a user by their ID.
     * 
     * @param string $userId The ID of the user.
     * @return WorkEntry|null The open WorkEntry entity or null if not found.
     */
    public function findOpenWorkEntryByUserId(string $userId): ?WorkEntry;

    /**
     * Find the previous work entry for a user before a given start date.
     * 
     * @param string $userId The ID of the user.
     * @param DateTimeImmutable $startDate The start date to compare.
     * @return WorkEntry|null The previous WorkEntry entity or null if not found.
     */
    public function findPreviousWorkEntry(string $userId, DateTimeImmutable $startDate): ?WorkEntry;

    /**
     * Find the next work entry for a user after a given end date.
     * 
     * @param string $userId The ID of the user.
     * @param DateTimeImmutable $endDate The end date to compare.
     * @return WorkEntry|null The next WorkEntry entity or null if not found.
     */
    public function findNextWorkEntry(string $userId, DateTimeImmutable $endDate): ?WorkEntry;

    /**
     * Get work entries for today for a user.
     * 
     * @param string $userId The ID of the user.
     * @return WorkEntry[] An array of WorkEntry entities for today.
     */
    public function getWorkEntriesForToday(string $userId): array;
}