<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Task;

use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\UseCase\Task\Create\Command;
use App\Model\Progress\UseCase\Task\Create\Handler;
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
class CreateController extends AbstractController
{
    #[Route('/api/v1/task/create', name: 'api.v1.task.create', methods: ['POST'])]
    public function create(
        Request           $request,
        Handler           $handler,
        ProjectRepository $projectRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $project = null;

        if (isset($data['project_id'])) {
            $project = $projectRepository->get(Ulid::fromString($data['project_id']));
            $this->denyAccessUnlessGranted(ProjectVoter::MANAGE_TASKS, $project);
        }

        $command = new Command(
            title: $data['title'],
            description: $data['description'] ?? null,
            userId: Ulid::fromString($this->getUser()->getUserIdentifier()),
            projectId: $project?->getId(),
        );

        $taskId = $handler->handle($command);

        return $this->json([
            'success' => true,
            'task_id' => $taskId->toRfc4122(),
        ]);
    }
}
