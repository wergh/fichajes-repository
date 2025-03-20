<?php

namespace App\Domain\User\ValueObject;

use App\Domain\Shared\ValueObject\IdGeneratorInterface;
use App\Domain\Shared\ValueObject\UuidValueObject;

/**
 * Value object representing a User ID.
 */
final class UserId extends UuidValueObject
{
    /**
     * Create a new UserId using an ID generator.
     * 
     * @param IdGeneratorInterface $generator The ID generator to use.
     * @return UserId The generated UserId.
     */
    public static function create(IdGeneratorInterface $generator): UserId
    {
        return new self($generator->generate());
    }

    /**
     * Create a UserId from a string.
     * 
     * @param string $id The string representation of the UserId.
     * @return UserId The UserId created from the string.
     */
    public static function fromString(string $id): UserId
    {
        return new self($id);
    }
}