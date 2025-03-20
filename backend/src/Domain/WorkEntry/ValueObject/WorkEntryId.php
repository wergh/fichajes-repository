<?php

namespace App\Domain\WorkEntry\ValueObject;

use App\Domain\Shared\ValueObject\IdGeneratorInterface;
use App\Domain\Shared\ValueObject\UuidValueObject;

/**
 * Value object representing a Work Entry ID.
 */
final class WorkEntryId extends UuidValueObject
{
    /**
     * Create a new WorkEntryId using an ID generator.
     * 
     * @param IdGeneratorInterface $generator The ID generator to use.
     * @return WorkEntryId The generated WorkEntryId.
     */
    public static function create(IdGeneratorInterface $generator): WorkEntryId
    {
        return new self($generator->generate());
    }

    /**
     * Create a WorkEntryId from a string.
     * 
     * @param string $id The string representation of the WorkEntryId.
     * @return WorkEntryId The WorkEntryId created from the string.
     */
    public static function fromString(string $id): WorkEntryId
    {
        return new self($id);
    }
}