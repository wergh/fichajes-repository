<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserWriteRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Doctrine implementation of UserWriteRepositoryInterface for writing user data.
 */
readonly class DoctrineUserWriteRepository implements UserWriteRepositoryInterface
{
    /**
     * Constructor for DoctrineUserWriteRepository.
     * 
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Save a user entity to the database.
     * 
     * @param User $user The user entity to save.
     * @param bool $flush Whether to flush changes immediately.
     */
    public function save(User $user, $flush = true): void
    {
        $this->entityManager->persist($user);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}