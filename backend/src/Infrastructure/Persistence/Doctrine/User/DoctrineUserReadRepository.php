<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserReadRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Doctrine implementation of UserReadRepositoryInterface for reading user data.
 */
readonly class DoctrineUserReadRepository implements UserReadRepositoryInterface
{
    /**
     * Constructor for DoctrineUserReadRepository.
     * 
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Retrieve all users.
     * 
     * @return User[] An array of User entities.
     */
    public function all(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    /**
     * Find a user by their ID.
     * 
     * @param string $id The ID of the user to find.
     * @return User|null The found User entity or null if not found.
     */
    public function findById(string $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }
}