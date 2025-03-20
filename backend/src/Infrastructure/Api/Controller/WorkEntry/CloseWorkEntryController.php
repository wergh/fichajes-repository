<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\WorkEntry;

use App\Application\Mapper\WorkEntry\WorkEntryDtoMapper;
use App\Application\UseCase\WorkEntry\CloseWorkEntryUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\WorkEntry\Exception\NotWorkEntryOpenException;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests to close a work entry.
 */
#[AsController]
class CloseWorkEntryController extends AbstractController
{
    /**
     * Constructor for CloseWorkEntryController.
     * 
     * @param CloseWorkEntryUseCase $closeWorkEntryUseCase Use case for closing a work entry.
     * @param WorkEntryDtoMapper $workEntryDtoMapper Mapper for converting WorkEntry entities to WorkEntryDto.
     */
    public function __construct(
        private readonly CloseWorkEntryUseCase $closeWorkEntryUseCase,
        private readonly WorkEntryDtoMapper    $workEntryDtoMapper
    )
    {
    }

    /**
     * Handle the request to close a work entry.
     * 
     * @param string $userId The ID of the user whose work entry is to be closed.
     * @return JsonResponse The JSON response.
     */
    #[Route('/work-entry/close/{userId}', methods: ['GET'])]
    public function _invoke(string $userId): JsonResponse
    {
        try {
            $workEntry = $this->closeWorkEntryUseCase->execute($userId);
        } catch (EntityNotFoundException|NotWorkEntryOpenException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $workEntryDto = $this->workEntryDtoMapper->toDTO($workEntry);
        return new JsonResponse(['message' => 'Work Entry closed successfully', 'data' => $workEntryDto], Response::HTTP_OK);
    }
}