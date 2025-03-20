<?php

declare(strict_types=1);

namespace App\Application\Dto\User;

/**
 * Data Transfer Object for User.
 */
class UserDto
{
    /**
     * Constructor for UserDto.
     * 
     * @param string $id The user ID.
     * @param string $name The user name.
     * @param array $workEntries The user's work entries.
     * @param int $totalTime The total time of work entries.
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array  $workEntries,
        public readonly int    $totalTime,
    )
    {
    }
}