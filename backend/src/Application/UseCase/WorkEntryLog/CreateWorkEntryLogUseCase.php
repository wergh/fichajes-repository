<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntryLog;

use App\Application\Command\WorkEntryLog\CreateWorkEntryLogCommand;
use App\Application\UseCase\User\GetUserByIdUseCase;
use App\Application\UseCase\WorkEntry\GetWorkEntryByIdUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\WorkEntry\Entity\WorkEntryLog;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryLogWriteRepositoryInterface;

/**
 * Use case for creating a work entry log.
 */
final readonly class CreateWorkEntryLogUseCase
{
    /**
     * Constructor for CreateWorkEntryLogUseCase.
     * 
     * @param WorkEntryLogWriteRepositoryInterface $workEntryLogWriteRepository Repository for writing work entry logs.
     * @param GetUserByIdUseCase $getUserByIdUseCase Use case for retrieving user by ID.
     * @param GetWorkEntryByIdUseCase $getWorkEntryByIdUseCase Use case for retrieving work entry by ID.
     */
    public function __construct(
        private WorkEntryLogWriteRepositoryInterface $workEntryLogWriteRepository,
        private GetUserByIdUseCase                   $getUserByIdUseCase,
        private GetWorkEntryByIdUseCase              $getWorkEntryByIdUseCase
    )
    {
    }

    /**
     * Execute the use case to create a work entry log.
     * 
     * @param CreateWorkEntryLogCommand $command The command containing work entry log creation data.
     * @throws UnauthorizedAccessToWorkEntry If the user is not authorized to access the work entry.
     * @throws EntityNotFoundException If the user or work entry is not found.
     */
    public function execute(CreateWorkEntryLogCommand $command): void
    {
        $user = $this->getUserByIdUseCase->execute($command->getUpdatedById());
        $workEntry = $this->getWorkEntryByIdUseCase->execute($command->getUpdatedById(), $command->getWorkEntryId());

        $workEntryLog = new WorkEntryLog(
            $user,
            $command->getStartTime(),
            $command->getEndTime(),
            $workEntry
        );

        $this->workEntryLogWriteRepository->save($workEntryLog);
    }
}