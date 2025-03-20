<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Application\Command\WorkEntryLog\CreateWorkEntryLogCommand;
use App\Application\UseCase\WorkEntryLog\CreateWorkEntryLogUseCase;
use App\Domain\WorkEntry\Events\WorkEntryUpdatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Listener for handling work entry updated events.
 */
#[AsMessageHandler]
class WorkEntryUpdatedListener
{
    /**
     * Constructor for WorkEntryUpdatedListener.
     * 
     * @param CreateWorkEntryLogUseCase $createWorkEntryLogUseCase Use case for creating work entry logs.
     */
    public function __construct(private readonly CreateWorkEntryLogUseCase $createWorkEntryLogUseCase) {}

    /**
     * Handle the work entry updated event.
     * 
     * @param WorkEntryUpdatedEvent $event The event containing work entry update data.
     */
    public function __invoke(WorkEntryUpdatedEvent $event): void
    {
        $command = new CreateWorkEntryLogCommand(
            $event->getWorkEntryId(),
            $event->getUserId(),
            $event->getStartDate(),
            $event->getEndDate(),
        );

        $this->createWorkEntryLogUseCase->execute($command);
    }
}