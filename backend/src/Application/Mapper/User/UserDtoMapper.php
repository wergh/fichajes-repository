<?php

declare(strict_types=1);

namespace App\Application\Mapper\User;

use App\Application\Dto\User\UserDto;
use App\Domain\User\Entity\User;
use App\Domain\WorkEntry\Entity\WorkEntry;

/**
 * Mapper class for converting User entities to UserDto.
 */
class UserDtoMapper
{
    /**
     * Convert a User entity to a UserDto.
     * 
     * @param User $user The user entity to convert.
     * @param int $totalTime The total time of work entries.
     * @return UserDto The converted UserDto.
     */
    public function toDTO(User $user, int $totalTime = 0): UserDto
    {
        return new UserDto(
            (string)$user->getId(),
            $user->getName(),
            $this->mapWorkEntries($user->getWorkEntries()->toArray()),
            $totalTime
        );
    }

    /**
     * Map an array of WorkEntry entities to an array of data.
     * 
     * @param WorkEntry[] $workEntries The array of work entries to map.
     * @return array The mapped array of work entry data.
     */
    private function mapWorkEntries(array $workEntries): array
    {
        return array_map(fn(WorkEntry $entry) => [
            'id' => (string) $entry->getId(),
            'startDate' => $entry->getStartDate()->format('Y-m-d H:i:s'),
            'endDate' => ($entry->getEndDate()) ? $entry->getEndDate()->format('Y-m-d H:i:s') : null,
        ], $workEntries);
    }
}