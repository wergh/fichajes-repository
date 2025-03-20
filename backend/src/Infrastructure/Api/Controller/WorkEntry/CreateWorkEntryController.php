<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\WorkEntry;

use App\Application\Command\WorkEntry\CreateWorkEntryCommand;
use App\Application\Mapper\WorkEntry\WorkEntryDtoMapper;
use App\Application\UseCase\WorkEntry\CreateWorkEntryUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\User\Exception\WorkEntryAlreadyOpenException;
use App\Infrastructure\Api\Request\WorkEntry\CreateWorkEntryRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Controller for handling requests to create a work entry.
 */
#[AsController]
class CreateWorkEntryController extends AbstractController
{
    /**
     * Constructor for CreateWorkEntryController.
     * 
     * @param CreateWorkEntryUseCase $createWorkEntryUseCase Use case for creating a work entry.
     * @param SerializerInterface $serializer Serializer for request data.
     * @param ValidatorInterface $validator Validator for request data.
     * @param WorkEntryDtoMapper $workEntryDtoMapper Mapper for converting WorkEntry entities to WorkEntryDto.
     */
    public function __construct(
        private readonly CreateWorkEntryUseCase $createWorkEntryUseCase,
        private readonly SerializerInterface    $serializer,
        private readonly ValidatorInterface     $validator,
        private readonly WorkEntryDtoMapper     $workEntryDtoMapper
    )
    {
    }

    /**
     * Handle the request to create a work entry.
     * 
     * @param Request $request The HTTP request.
     * @return JsonResponse The JSON response.
     */
    #[Route('/work-entry', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $createWorkEntryRequest = $this->serializer->deserialize(
            $request->getContent(),
            CreateWorkEntryRequest::class,
            'json'
        );

        $errors = $this->validator->validate($createWorkEntryRequest);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $command = new CreateWorkEntryCommand($createWorkEntryRequest->getUserId());
            $workEntry = $this->createWorkEntryUseCase->execute($command);
        } catch (ValidationFailedException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (WorkEntryAlreadyOpenException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $workEntryDto = $this->workEntryDtoMapper->toDTO($workEntry);

        return new JsonResponse(['message' => 'Work entry created successfully', 'data' => $workEntryDto], Response::HTTP_CREATED);
    }
}