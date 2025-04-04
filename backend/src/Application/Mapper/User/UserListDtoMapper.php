<?php

declare(strict_types=1);

namespace App\Application\Mapper\User;

use App\Application\Dto\User\UserListDto;
use App\Domain\User\Entity\User;

/**
 * Mapper class for converting User entities to UserListDto.
 */
class UserListDtoMapper
{
    /**
     * Convert a User entity to a UserListDto.
     * 
     * @param User $user The user entity to convert.
     * @return UserListDto The converted UserListDto.
     */
    public function toDTO(User $user): UserListDto
    {
        return new UserListDto(
            (string) $user->getId(),
            $user->getName(),
        );
    }
}