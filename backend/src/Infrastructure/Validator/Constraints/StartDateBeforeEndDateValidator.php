<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use DateTimeImmutable;

/**
 * Validator for the StartDateBeforeEndDate constraint.
 */
class StartDateBeforeEndDateValidator extends ConstraintValidator
{
    /**
     * Validate the given value against the StartDateBeforeEndDate constraint.
     * 
     * @param mixed $value The value to validate.
     * @param Constraint $constraint The constraint for the validation.
     * @throws UnexpectedTypeException If the constraint is not of type StartDateBeforeEndDate.
     * @throws UnexpectedValueException If the start or end date is not a DateTimeImmutable instance.
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof StartDateBeforeEndDate) {
            throw new UnexpectedTypeException($constraint, StartDateBeforeEndDate::class);
        }

        if (null === $value || !is_array($value)) {
            return;
        }

        $startDate = $value['startDate'] ?? null;
        $endDate = $value['endDate'] ?? null;

        if (!$startDate instanceof DateTimeImmutable) {
            throw new UnexpectedValueException($startDate, DateTimeImmutable::class);
        }

        if (!$endDate instanceof DateTimeImmutable) {
            throw new UnexpectedValueException($endDate, DateTimeImmutable::class);
        }

        // Compare the dates
        if ($startDate >= $endDate) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ startDate }}', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('{{ endDate }}', $endDate->format('Y-m-d H:i:s'))
                ->addViolation();
        }
    }
}