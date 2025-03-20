<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntry;

use App\Application\Command\WorkEntry\UpdateWorkEntryCommand;
use App\Application\Validator\WorkEntry\UpdateWorkEntryValidatorInterface;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\User\Exception\EndDateInTheFutureNotAllowed;
use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Exception\NotOverlapException;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use App\Domain\WorkEntry\Exception\WorkEntryIsAlreadyOpenException;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;
use App\Domain\WorkEntry\Repository\WorkEntryWriteRepositoryInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Use case for updating a work entry.
 */
final readonly class UpdateWorkEntryUseCase
{
    /**
     * Constructor for UpdateWorkEntryUseCase.
     * 
     * @param GetWorkEntryByIdUseCase $getWorkEntryByIdUseCase Use case for retrieving work entry by ID.
     * @param WorkEntryWriteRepositoryInterface $workEntryWriteRepository Repository for writing work entries.
     * @param WorkEntryReadRepositoryInterface $workEntryReadRepository Repository for reading work entries.
     * @param UpdateWorkEntryValidatorInterface $validator Validator for work entry updates.
     * @param MessageBusInterface $eventBus Event bus for dispatching events.
     */
    public function __construct(
        private GetWorkEntryByIdUseCase           $getWorkEntryByIdUseCase,
        private WorkEntryWriteRepositoryInterface $workEntryWriteRepository,
        private WorkEntryReadRepositoryInterface  $workEntryReadRepository,
        private UpdateWorkEntryValidatorInterface $validator,
        private MessageBusInterface               $eventBus
    )
    {
    }

    /**
     * Execute the use case to update a work entry.
     * 
     * @param UpdateWorkEntryCommand $command The command containing work entry update data.
     * @return WorkEntry The updated work entry entity.
     * @throws WorkEntryIsAlreadyOpenException If the work entry is already open.
     * @throws UnauthorizedAccessToWorkEntry If the user is not authorized to update the work entry.
     * @throws ExceptionInterface If an error occurs during event dispatching.
     * @throws EntityNotFoundException If the work entry is not found.
     * @throws NotOverlapException If the work entry overlaps with another entry.
     * @throws EndDateInTheFutureNotAllowed If the end date is set in the future.
     */
    public function execute(UpdateWorkEntryCommand $command): WorkEntry
    {
        $this->validator->validate($command);

        $workEntry = $this->getWorkEntryByIdUseCase->execute($command->getUserId(), $command->getWorkEntryId());

        if ($workEntry->isOpen()) {
            throw new WorkEntryIsAlreadyOpenException('Work entry is already open');
        }

        $previousEntry = $this->workEntryReadRepository->findPreviousWorkEntry($command->getUserId(), $workEntry->getStartDate());
        $nextEntry = $this->workEntryReadRepository->findNextWorkEntry($command->getUserId(), $workEntry->getEndDate());

        $workEntry->update($command->getUserId(), $command->getStartDate(), $command->getEndDate(), $previousEntry, $nextEntry);

        $this->workEntryWriteRepository->save($workEntry);

        foreach ($workEntry->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }

        return $workEntry;
    }
}