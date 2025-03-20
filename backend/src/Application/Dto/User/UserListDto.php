<?php

declare(strict_types=1);

namespace App\Application\Dto\User;

use App\Domain\User\ValueObject\UserId;

/**
 * Data Transfer Object for User List.
 */
class UserListDto
{
    /**
     * Constructor for UserListDto.
     * 
     * @param string $id The user ID.
     * @param string $name The user name.
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {}
}