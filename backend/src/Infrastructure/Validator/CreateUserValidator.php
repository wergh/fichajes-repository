<?php

namespace App\Infrastructure\Validator;

use App\Application\Command\User\CreateUserCommand;
use App\Application\Validator\User\CreateUserValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Validator for creating a user.
 */
class CreateUserValidator implements CreateUserValidatorInterface
{
    /**
     * Constructor for CreateUserValidator.
     * 
     * @param ValidatorInterface $validator The validator interface for validating constraints.
     */
    public function __construct(private readonly ValidatorInterface $validator) {}

    /**
     * Validate the CreateUserCommand.
     * 
     * @param CreateUserCommand $command The command to validate.
     * @throws ValidationFailedException If validation fails.
     */
    public function validate(CreateUserCommand $command): void
    {
        $constraints = new Assert\Collection([
            'name' => [new Assert\NotBlank(), new Assert\Length(['min' => 3])]
        ]);

        $violations = $this->validator->validate([
            'name' => $command->getName(),
        ], $constraints);

        if ($violations->count() > 0) {
            throw new ValidationFailedException($command, $violations);
        }
    }
}