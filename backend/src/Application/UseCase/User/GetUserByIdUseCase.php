<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserReadRepositoryInterface;

/**
 * Use case for retrieving a user by their ID.
 */
final readonly class GetUserByIdUseCase
{
    /**
     * Constructor for GetUserByIdUseCase.
     * 
     * @param UserReadRepositoryInterface $userReadRepository Repository for reading user data.
     */
    public function __construct(private UserReadRepositoryInterface $userReadRepository)
    {
    }

    /**
     * Execute the use case to retrieve a user by their ID.
     * 
     * @param string $id The ID of the user to retrieve.
     * @return User The retrieved user entity.
     * @throws EntityNotFoundException If the user is not found.
     */
    public function execute(string $id): User
    {
        $user = $this->userReadRepository->findById($id);
        if (!$user) {
            throw new EntityNotFoundException("User not found");
        }
        return $user;
    }
}