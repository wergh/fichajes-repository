<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntry;

use App\Application\Command\WorkEntry\CreateWorkEntryCommand;
use App\Application\UseCase\User\GetUserByIdUseCase;
use App\Application\Validator\WorkEntry\CreateWorkEntryValidatorInterface;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\Shared\ValueObject\IdGeneratorInterface;
use App\Domain\User\Exception\WorkEntryAlreadyOpenException;
use App\Domain\User\Service\UserDomainService;
use App\Domain\WorkEntry\Entity\WorkEntry;
use App\Domain\WorkEntry\Repository\WorkEntryWriteRepositoryInterface;
use App\Domain\WorkEntry\ValueObject\WorkEntryId;
use DateTimeImmutable;

/**
 * Use case for creating a new work entry.
 */
final class CreateWorkEntryUseCase
{
    /**
     * Constructor for CreateWorkEntryUseCase.
     * 
     * @param WorkEntryWriteRepositoryInterface $workEntryWriteRepository Repository for writing work entries.
     * @param CreateWorkEntryValidatorInterface $validator Validator for work entry creation.
     * @param GetUserByIdUseCase $getUserByIdUseCase Use case for retrieving user by ID.
     * @param IdGeneratorInterface $idGenerator ID generator for creating work entry IDs.
     * @param UserDomainService $userDomainService Service for user domain operations.
     */
    public function __construct(
        private WorkEntryWriteRepositoryInterface $workEntryWriteRepository,
        private CreateWorkEntryValidatorInterface $validator,
        private GetUserByIdUseCase                $getUserByIdUseCase,
        private IdGeneratorInterface              $idGenerator,
        private UserDomainService                 $userDomainService,
    )
    {
    }

    /**
     * Execute the use case to create a new work entry.
     * 
     * @param CreateWorkEntryCommand $command The command containing work entry creation data.
     * @return WorkEntry The created work entry entity.
     * @throws WorkEntryAlreadyOpenException If the user already has an open work entry.
     * @throws EntityNotFoundException If the user is not found.
     */
    public function execute(CreateWorkEntryCommand $command): WorkEntry
    {
        $this->validator->validate($command);
        $user = $this->getUserByIdUseCase->execute($command->getUserId());

        $this->userDomainService->validateUserCanCreateWorkEntry($user);

        $workEntry = new WorkEntry(
            id: WorkEntryId::create($this->idGenerator),
            user: $user,
            startDate: new DateTimeImmutable()
        );

        $this->workEntryWriteRepository->save($workEntry);
        return $workEntry;
    }
}