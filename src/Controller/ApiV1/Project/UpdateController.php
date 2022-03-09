<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project;

use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\UseCase\Project\Update\Command;
use App\Model\Progress\UseCase\Project\Update\Handler;
use App\Model\Progress\ValueObject\Color;
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
    #[Route('/api/v1/project/{id}/update', name: 'api.v1.project.update', methods: ['POST'])]
    public function create(
        string            $id,
        Request           $request,
        Handler           $handler,
        ProjectRepository $projectRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $project = $projectRepository->get(Ulid::fromString($id));

        $this->denyAccessUnlessGranted(ProjectVoter::MANAGE_TASKS, $project);

        $command = new Command(
            id: $project->getId(),
            title: $data['title'],
            description: $data['description'],
            color: Color::from($data['color']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
