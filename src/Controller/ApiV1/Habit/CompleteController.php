<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Model\Progress\UseCase\Habit\Complete\Command;
use App\Model\Progress\UseCase\Habit\Complete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CompleteController extends AbstractController
{
    #[Route('/api/v1/habit/{id}/complete', name: 'api.v1.habit.complete', methods: ['POST'])]
    public function complete(
        string  $id,
        Request $request,
        Handler $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Command(
            habitId: Ulid::fromString($id),
            completedAt: new \DateTimeImmutable($data['completed_at']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
