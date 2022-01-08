<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Task;

use App\Model\Progress\UseCase\Task\Complete\Command;
use App\Model\Progress\UseCase\Task\Complete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CompleteController extends AbstractController
{
    #[Route('/api/v1/task/{id}/complete', name: 'api.v1.task.complete', methods: ['POST'])]
    public function complete(string $id, Handler $handler): JsonResponse
    {
        $command = new Command(
            taskId: Ulid::fromString($id),
            completedAt: new \DateTimeImmutable(),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
