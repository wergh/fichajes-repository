<?php

declare(strict_types=1);

namespace App\Domain\WorkEntry\Service;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\WorkEntryAlreadyOpenException;
use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;

/**
 * Service class for work entry domain operations.
 */
final class WorkEntryDomainService
{
    /**
     * Constructor for WorkEntryDomainService.
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
        if ($this->workEntryRepository->findOpenWorkEntryByUserId((string) $user->getId())) {
            throw new WorkEntryAlreadyOpenException('User already has an open work entry.');
        }
    }

    /**
     * Check if a user can access a specific work entry.
     * 
     * @param User $user The user attempting to access the work entry.
     * @param WorkEntry $workEntry The work entry to access.
     * @return bool True if access is allowed, false otherwise.
     * @throws UnauthorizedAccessToWorkEntry If the user is not authorized to access the work entry.
     */
    public function canAccessToWorkEntry(User $user, WorkEntry $workEntry): bool
    {
        //Aquí se deberían ejecutar la lógica de acceso al WorkEntry, por ejemplo
        //Solo podría recuperar los datos de una work entry el propio usuario
        //y aquellos usuarios que tuvieran el permiso para poder acceder a work entries ajenas a
        //las suyas.
        if ($user->getId() !== $workEntry->getUser()->getId()) {
            throw new UnauthorizedAccessToWorkEntry('Unauthorized access to WorkEntry');
        }

        return true;
    }
}