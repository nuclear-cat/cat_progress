<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Task;

use App\Model\Progress\Repository\TaskRepository;
use App\Model\Progress\UseCase\Task\Update\Command;
use App\Model\Progress\UseCase\Task\Update\Handler;
use App\Security\Api\Progress\ProjectVoter;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class UpdateController extends AbstractController
{
    #[Route('/api/v1/task/{id}/update', name: 'api.v1.task.update', methods: ['POST'])]
    public function update(
        Request        $request,
        string         $id,
        Handler        $handler,
        TaskRepository $taskRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $task = $taskRepository->get(Ulid::fromString($id));

        if ($task->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::MANAGE_TASKS, $task->getProject());
        }

        $command = new Command(
            taskId: $task->getId(),
            title: $data['title'],
            description: $data['description'] ?? null,
            projectId: $data['project_id'] ? Ulid::fromString($data['project_id']) : null,
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
