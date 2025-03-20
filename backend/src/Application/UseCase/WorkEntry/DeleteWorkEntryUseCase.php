<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntry;

use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryWriteRepositoryInterface;

/**
 * Use case for deleting a work entry.
 */
final readonly class DeleteWorkEntryUseCase
{
    /**
     * Constructor for DeleteWorkEntryUseCase.
     * 
     * @param GetWorkEntryByIdUseCase $getWorkEntryByIdUseCase Use case for retrieving work entry by ID.
     * @param WorkEntryWriteRepositoryInterface $workEntryWriteRepository Repository for writing work entries.
     */
    public function __construct(
        private GetWorkEntryByIdUseCase           $getWorkEntryByIdUseCase,
        private WorkEntryWriteRepositoryInterface $workEntryWriteRepository,
    )
    {
    }

    /**
     * Execute the use case to delete a work entry.
     * 
     * @param string $userId The ID of the user associated with the work entry.
     * @param string $workEntryId The ID of the work entry to delete.
     * @throws UnauthorizedAccessToWorkEntry If the user is not authorized to delete the work entry.
     * @throws EntityNotFoundException If the work entry is not found.
     */
    public function execute($userId, $workEntryId): void
    {
        $workEntry = $this->getWorkEntryByIdUseCase->execute($userId, $workEntryId);

        $workEntry->delete();

        $this->workEntryWriteRepository->save($workEntry);
    }
}