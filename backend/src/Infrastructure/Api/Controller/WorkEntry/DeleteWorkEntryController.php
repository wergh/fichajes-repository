<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\WorkEntry;

use App\Application\UseCase\WorkEntry\DeleteWorkEntryUseCase;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\WorkEntry\Exception\UnauthorizedAccessToWorkEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests to delete a work entry.
 */
#[AsController]
class DeleteWorkEntryController extends AbstractController
{
    /**
     * Constructor for DeleteWorkEntryController.
     * 
     * @param DeleteWorkEntryUseCase $deleteWorkEntryUseCase Use case for deleting a work entry.
     */
    public function __construct(
        private readonly DeleteWorkEntryUseCase $deleteWorkEntryUseCase,
    )
    {
    }

    /**
     * Handle the request to delete a work entry.
     * 
     * @param string $userId The ID of the user associated with the work entry.
     * @param string $workEntryId The ID of the work entry to delete.
     * @return JsonResponse The JSON response.
     */
    #[Route('/work-entry/delete/{userId}/{workEntryId}', methods: ['DELETE'])]
    public function _invoke($userId, $workEntryId): JsonResponse
    {
        try {
            $this->deleteWorkEntryUseCase->execute($userId, $workEntryId);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (UnauthorizedAccessToWorkEntry $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['message' => 'Work Entry deleted successfully'], Response::HTTP_OK);
    }
}