<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\WorkEntryLog;

use App\Domain\WorkEntry\Entity\WorkEntryLog;
use App\Domain\WorkEntry\Repository\WorkEntryLogWriteRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Doctrine implementation of WorkEntryLogWriteRepositoryInterface for writing work entry log data.
 */
readonly class DoctrineWorkEntryLogWriteRepository implements WorkEntryLogWriteRepositoryInterface
{
    /**
     * Constructor for DoctrineWorkEntryLogWriteRepository.
     * 
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Save a work entry log entity to the database.
     * 
     * @param WorkEntryLog $workEntryLog The work entry log entity to save.
     * @param bool $flush Whether to flush changes immediately.
     */
    public function save(WorkEntryLog $workEntryLog, $flush = true): void
    {
        $this->entityManager->persist($workEntryLog);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}