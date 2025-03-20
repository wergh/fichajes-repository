<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\WorkEntry;

use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

/**
 * Doctrine implementation of WorkEntryReadRepositoryInterface for reading work entry data.
 */
readonly class DoctrineWorkEntryReadRepository implements WorkEntryReadRepositoryInterface
{
    /**
     * Constructor for DoctrineWorkEntryReadRepository.
     * 
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Retrieve all work entries for a given user.
     * 
     * @param string $userId The ID of the user whose work entries are to be retrieved.
     * @return WorkEntry[] An array of WorkEntry entities.
     */
    public function all(string $userId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('w')
            ->from(WorkEntry::class, 'w')
            ->where('w.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a work entry by its ID.
     * 
     * @param string $id The ID of the work entry to find.
     * @return WorkEntry|null The found WorkEntry entity or null if not found.
     */
    public function findById(string $id): ?WorkEntry
    {
        return $this->entityManager->getRepository(WorkEntry::class)->find($id);
    }

    /**
     * Find an open work entry for a given user.
     * 
     * @param string $userId The ID of the user whose open work entry is to be found.
     * @return WorkEntry|null The found open WorkEntry entity or null if not found.
     */
    public function findOpenWorkEntryByUserId(string $userId): ?WorkEntry
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('w')
            ->from(WorkEntry::class, 'w')
            ->where('w.user = :userId')
            ->andWhere('w.endDate IS NULL')
            ->andWhere('w.deletedAt IS NULL')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find the previous work entry for a given user before a specified start date.
     * 
     * @param string $userId The ID of the user whose previous work entry is to be found.
     * @param DateTimeImmutable $startDate The start date to compare against.
     * @return WorkEntry|null The found previous WorkEntry entity or null if not found.
     */
    public function findPreviousWorkEntry(string $userId, DateTimeImmutable $startDate): ?WorkEntry
    {
        return $this->entityManager->createQueryBuilder()
            ->select('w')
            ->from(WorkEntry::class, 'w')
            ->where('w.user = :userId')
            ->andWhere('w.endDate <= :startDate')
            ->andWhere('w.deletedAt IS NULL')
            ->orderBy('w.endDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('userId', $userId)
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find the next work entry for a given user after a specified end date.
     * 
     * @param string $userId The ID of the user whose next work entry is to be found.
     * @param DateTimeImmutable $endDate The end date to compare against.
     * @return WorkEntry|null The found next WorkEntry entity or null if not found.
     */
    public function findNextWorkEntry(string $userId, DateTimeImmutable $endDate): ?WorkEntry
    {
        return $this->entityManager->createQueryBuilder()
            ->select('w')
            ->from(WorkEntry::class, 'w')
            ->where('w.user = :userId')
            ->andWhere('w.startDate >= :endDate')
            ->andWhere('w.deletedAt IS NULL')
            ->orderBy('w.startDate', 'ASC')
            ->setMaxResults(1)
            ->setParameter('userId', $userId)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retrieve work entries for today for a given user.
     * 
     * @param string $userId The ID of the user whose work entries for today are to be retrieved.
     * @return WorkEntry[] An array of WorkEntry entities for today.
     */
    public function getWorkEntriesForToday(string $userId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('w')
            ->from(WorkEntry::class, 'w')
            ->where('w.user = :userId')
            ->andWhere('w.deletedAt IS NULL')
            ->andWhere('(
                (w.startDate >= :startOfDay AND w.startDate <= :endOfDay) OR
                (w.startDate <= :startOfDay AND (w.endDate IS NULL OR (w.endDate >= :startOfDay AND w.endDate <= :endOfDay)))
            )')
            ->setParameter('userId', $userId)
            ->setParameter('startOfDay', (new \DateTimeImmutable('today'))->setTime(0, 0))
            ->setParameter('endOfDay', (new \DateTimeImmutable('tomorrow'))->setTime(0, 0)->modify('-1 second'))
            ->getQuery()
            ->getResult();
    }
}