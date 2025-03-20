<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Repository;

use App\Domain\WorkEntry\Entity\WorkEntryLog;

/**
 * Interface for writing work entry logs to a repository.
 */
interface WorkEntryLogWriteRepositoryInterface
{
    /**
     * Save a work entry log to the repository.
     * 
     * @param WorkEntryLog $workEntryLog The work entry log to save.
     * @param bool $flush Whether to flush changes immediately.
     */
    public function save(WorkEntryLog $workEntryLog, $flush = true): void;
}