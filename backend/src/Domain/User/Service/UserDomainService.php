<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\WorkEntryAlreadyOpenException;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;

/**
 * Service class for user domain operations.
 */
class UserDomainService
{
    /**
     * Constructor for UserDomainService.
     * 
     * @param WorkEntryReadRepositoryInterface $workEntryRepository Repository for reading work entries.
     */
    public function __construct(
        private readonly WorkEntryReadRepositoryInterface $workEntryRepository,
    )
    {
    }

    /**
     * Validate if a user can create a new work entry.
     * 
     * @param User $user The user entity to validate.
     * @throws WorkEntryAlreadyOpenException If the user already has an open work entry.
     */
    public function validateUserCanCreateWorkEntry(User $user): void
    {
        if($this->workEntryRepository->findOpenWorkEntryByUserId((string) $user->getId())) {
            throw new WorkEntryAlreadyOpenException('User already has an open work entry.');
        }
    }
}