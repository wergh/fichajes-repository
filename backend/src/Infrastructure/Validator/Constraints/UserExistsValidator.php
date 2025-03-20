<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Constraints;

use App\Domain\User\Repository\UserReadRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Validator for the UserExists constraint.
 */
class UserExistsValidator extends ConstraintValidator
{
    /**
     * Constructor for UserExistsValidator.
     * 
     * @param UserReadRepositoryInterface $userReadRepository Repository for reading user data.
     */
    public function __construct(private readonly UserReadRepositoryInterface $userReadRepository)
    {
    }

    /**
     * Validate the given value against the UserExists constraint.
     * 
     * @param mixed $value The value to validate.
     * @param Constraint $constraint The constraint for the validation.
     * @throws UnexpectedTypeException If the constraint is not of type UserExists.
     * @throws UnexpectedValueException If the value is not a string.
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UserExists) {
            throw new UnexpectedTypeException($constraint, UserExists::class);
        }
        if (null === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->userReadRepository->findById($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}