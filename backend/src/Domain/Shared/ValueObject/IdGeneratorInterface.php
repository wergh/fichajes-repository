<?php

namespace App\Domain\Shared\ValueObject;

/**
 * Interface for generating unique identifiers.
 */
interface IdGeneratorInterface
{
    /**
     * Generate a unique identifier.
     * 
     * @return string The generated unique identifier.
     */
    public function generate(): string;
}
