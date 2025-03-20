<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Repository;

use App\Domain\WorkEntry\Entity\WorkEntry;

/**
 * Interface for writing work entries to a repository.
 */
interface WorkEntryWriteRepositoryInterface
{
    /**
     * Save a work entry to the repository.
     * 
     * @param WorkEntry $workEntry The work entry to save.
     * @param bool $flush Whether to flush changes immediately.
     */
    public function save(WorkEntry $workEntry, $flush = true): void;
}