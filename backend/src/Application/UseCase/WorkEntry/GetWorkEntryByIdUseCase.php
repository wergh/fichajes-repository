<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntry;

use App\Application\UseCase\User\GetUserByIdUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;
use App\Domain\WorkEntry\Service\WorkEntryDomainService;

/**
 * Use case for retrieving a work entry by its ID.
 */
final readonly class GetWorkEntryByIdUseCase
{
    /**
     * Constructor for GetWorkEntryByIdUseCase.
     * 
     * @param GetUserByIdUseCase $getUserByIdUseCase Use case for retrieving user by ID.
     * @param WorkEntryReadRepositoryInterface $workEntryReadRepository Repository for reading work entries.
     * @param WorkEntryDomainService $workEntryDomainService Service for work entry domain operations.
     */
    public function __construct(
        private GetUserByIdUseCase               $getUserByIdUseCase,
        private WorkEntryReadRepositoryInterface $workEntryReadRepository,
        private WorkEntryDomainService           $workEntryDomainService,
    )
    {
    }

    /**
     * Execute the use case to retrieve a work entry by its ID.
     * 
     * @param string $userId The ID of the user associated with the work entry.
     * @param string $workEntryId The ID of the work entry to retrieve.
     * @return WorkEntry The retrieved work entry.
     * @throws UnauthorizedAccessToWorkEntry If the user is not authorized to access the work entry.
     * @throws EntityNotFoundException If the work entry is not found.
     */
    public function execute($userId, $workEntryId): WorkEntry
    {
        $user = $this->getUserByIdUseCase->execute($userId);

        $workEntry = $this->workEntryReadRepository->findById($workEntryId);
        if (!$workEntry) {
            throw new EntityNotFoundException('Work entry not found');
        }

        $this->workEntryDomainService->canAccessToWorkEntry($user, $workEntry);

        return $workEntry;
    }
}