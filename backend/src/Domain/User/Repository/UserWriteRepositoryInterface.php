<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

/**
 * Interface for writing user data to a repository.
 */
interface UserWriteRepositoryInterface
{
    /**
     * Save a user entity to the repository.
     * 
     * @param User $user The user entity to save.
     * @param bool $flush Whether to flush changes immediately.
     */
    public function save(User $user, $flush = true): void;
}