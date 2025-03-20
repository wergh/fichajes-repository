<?php

declare(strict_types=1);

namespace App\Application\UseCase\WorkEntry;

use App\Application\UseCase\User\GetUserByIdUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use Doctrine\Common\Collections\Collection;

/**
 * Use case for retrieving work entries for a user.
 */
final readonly class GetUserWorkEntriesUseCase
{
    /**
     * Constructor for GetUserWorkEntriesUseCase.
     * 
     * @param GetUserByIdUseCase $getUserByIdUseCase Use case for retrieving user by ID.
     */
    public function __construct(
        private GetUserByIdUseCase $getUserByIdUseCase
    )
    {
    }

    /**
     * Execute the use case to retrieve work entries for a user.
     * 
     * @param string $id The ID of the user whose work entries are to be retrieved.
     * @return Collection The collection of work entries.
     * @throws EntityNotFoundException If the user is not found.
     */
    public function execute(string $id): Collection
    {
        $user = $this->getUserByIdUseCase->execute($id);
        return $user->getWorkEntries();
    }
}