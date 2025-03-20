<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

/**
 * Interface for reading user data from a repository.
 */
interface UserReadRepositoryInterface
{
    /**
     * Retrieve all users.
     * 
     * @return User[] An array of User entities.
     */
    public function all(): array;

    /**
     * Find a user by their ID.
     * 
     * @param string $id The ID of the user to find.
     * @return User|null The User entity or null if not found.
     */
    public function findById(string $id): ?User;
}