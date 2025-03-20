<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

/**
 * Abstract class representing a UUID value object.
 */
abstract class UuidValueObject
{

    protected string $value;

    /**
     * Constructor for UuidValueObject.
     * 
     * @param string $value The UUID value.
     */
    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get the UUID value.
     * 
     * @return string The UUID value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Check if this UUID is equal to another UUID.
     * 
     * @param UuidValueObject $other The other UUID value object to compare.
     * @return bool True if equal, false otherwise.
     */
    public function equals(UuidValueObject $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Convert the UUID value to a string.
     * 
     * @return string The UUID value as a string.
     */
    public function __toString(): string
    {
        return $this->value;
    }

}
