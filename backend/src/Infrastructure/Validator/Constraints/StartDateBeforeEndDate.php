<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Constraint to ensure that the start date is before the end date.
 */
#[Attribute]
class StartDateBeforeEndDate extends Constraint
{
    public string $message = 'The start date "{{ startDate }}" must be before the end date "{{ endDate }}".';

    /**
     * Constructor for StartDateBeforeEndDate constraint.
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