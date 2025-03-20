<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Application\Command\User\CreateUserCommand;
use App\Application\Validator\User\CreateUserValidatorInterface;
use App\Domain\Shared\ValueObject\IdGeneratorInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserWriteRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

/**
 * Use case for creating a new user.
 */
final class CreateUserUseCase
{
    /**
     * Constructor for CreateUserUseCase.
     * 
     * @param UserWriteRepositoryInterface $userWriteRepository Repository for writing user data.
     * @param CreateUserValidatorInterface $validator Validator for user creation.
     * @param IdGeneratorInterface $idGenerator ID generator for creating user IDs.
     */
    public function __construct(
        private readonly UserWriteRepositoryInterface $userWriteRepository,
        private readonly CreateUserValidatorInterface $validator,
        private readonly IdGeneratorInterface         $idGenerator
    )
    {
    }

    /**
     * Execute the use case to create a new user.
     * 
     * @param CreateUserCommand $command The command containing user creation data.
     * @return User The created user entity.
     */
    public function execute(CreateUserCommand $command): User
    {
        $this->validator->validate($command);

        $user = new User(
            id: UserId::create($this->idGenerator),
            name: $command->getName(),
        );

        $this->userWriteRepository->save($user);

        return $user;
    }
}