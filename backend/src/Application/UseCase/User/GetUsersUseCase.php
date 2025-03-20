<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\User\Repository\UserReadRepositoryInterface;

/**
 * Use case for retrieving all users.
 */
final readonly class GetUsersUseCase
{
    /**
     * Constructor for GetUsersUseCase.
     * 
     * @param UserReadRepositoryInterface $userReadRepository Repository for reading user data.
     */
    public function __construct(private UserReadRepositoryInterface $userReadRepository)
    {
    }

    /**
     * Execute the use case to retrieve all users.
     * 
     * @return array An array of User entities.
     */
    public function execute(): array
    {
        return $this->userReadRepository->all();
    }
}