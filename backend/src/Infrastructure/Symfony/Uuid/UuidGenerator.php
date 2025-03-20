<?php

namespace App\Infrastructure\Symfony\Uuid;

use App\Domain\Shared\ValueObject\IdGeneratorInterface;
use Symfony\Component\Uid\Uuid;

/**
 * UUID generator implementation using Symfony's Uuid component.
 */
class UuidGenerator implements IdGeneratorInterface
{
    /**
     * Generate a new UUID.
     * 
     * @return string The generated UUID in RFC 4122 format.
     */
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}