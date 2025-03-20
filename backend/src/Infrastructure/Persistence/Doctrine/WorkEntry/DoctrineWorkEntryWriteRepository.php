<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\WorkEntry;

use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryWriteRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Doctrine implementation of WorkEntryWriteRepositoryInterface for writing work entry data.
 */
readonly class DoctrineWorkEntryWriteRepository implements WorkEntryWriteRepositoryInterface
{
    /**
     * Constructor for DoctrineWorkEntryWriteRepository.
     * 
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Save a work entry entity to the database.
     * 
     * @param WorkEntry $workEntry The work entry entity to save.
     * @param bool $flush Whether to flush changes immediately.
     */
    public function save(WorkEntry $workEntry, $flush = true): void
    {
        $this->entityManager->persist($workEntry);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}