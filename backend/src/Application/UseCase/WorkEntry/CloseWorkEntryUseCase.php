<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntry;

use App\Application\UseCase\User\GetUserByIdUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Exception\NotWorkEntryOpenException;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;
use App\Domain\WorkEntry\Repository\WorkEntryWriteRepositoryInterface;

/**
 * Use case for closing an open work entry.
 */
final readonly class CloseWorkEntryUseCase
{
    /**
     * Constructor for CloseWorkEntryUseCase.
     * 
     * @param GetUserByIdUseCase $getUserByIdUseCase Use case for retrieving user by ID.
     * @param WorkEntryReadRepositoryInterface $workEntryReadRepository Repository for reading work entries.
     * @param WorkEntryWriteRepositoryInterface $workEntryWriteRepository Repository for writing work entries.
     */
    public function __construct(
        private GetUserByIdUseCase                $getUserByIdUseCase,
        private WorkEntryReadRepositoryInterface  $workEntryReadRepository,
        private WorkEntryWriteRepositoryInterface $workEntryWriteRepository,
    )
    {
    }

    /**
     * Execute the use case to close an open work entry.
     * 
     * @param string $userId The ID of the user whose work entry is to be closed.
     * @return WorkEntry The closed work entry.
     * @throws NotWorkEntryOpenException If no work entry is open.
     * @throws EntityNotFoundException If the user is not found.
     */
    public function execute($userId): WorkEntry
    {
        $user = $this->getUserByIdUseCase->execute($userId);

        $workEntry = $this->workEntryReadRepository->findOpenWorkEntryByUserId((string)$user->getId());

        if (!$workEntry) {
            throw new NotWorkEntryOpenException('No work entry open');
        }

        $workEntry->close();
        $this->workEntryWriteRepository->save($workEntry);

        return $workEntry;
    }
}