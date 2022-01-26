<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Task;

use App\Model\Progress\Repository\TaskRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Task\Incomplete\Command;
use App\Model\Progress\UseCase\Task\Incomplete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class IncompleteController extends AbstractController
{
    #[Route('/api/v1/task/{id}/incomplete', name: 'api.v1.task.incomplete', methods: ['POST'])]
    public function incomplete(
        string         $id,
        Handler        $handler,
        UserRepository $userRepository,
        TaskRepository $taskRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $task = $taskRepository->getByIdAndUser(Ulid::fromString($id), $user);

        $command = new Command(
            taskId: $task->getId(),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
