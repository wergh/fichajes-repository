<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Constraint for validating DateTimeImmutable type.
 */
#[Attribute]
class DateTimeImmutableType extends Constraint
{
    public string $message = 'The value "{{ value }}" is not a valid instance of DateTimeImmutable.';

    /**
     * Constructor for DateTimeImmutableType constraint.
     * 
     * @param string|null $message Custom error message.
     * @param array|null $groups Validation groups.
     * @param mixed|null $payload Additional payload.
     */
    public function __construct(string $message = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        if ($message) {
            $this->message = $message;
        }
    }
}