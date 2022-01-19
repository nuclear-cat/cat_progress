<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\UseCase\Habit\Incomplete\Command;
use App\Model\Progress\UseCase\Habit\Incomplete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class IncompleteController extends AbstractController
{
    #[Route('/api/v1/habit/{id}/incomplete', name: 'api.v1.habit.incomplete', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['POST'])]
    public function incomplete(
        string  $id,
        Request $request,
        Handler $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Command(
            habitId: Ulid::fromString($id),
            completionId: Ulid::fromString($data['completion_id']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
