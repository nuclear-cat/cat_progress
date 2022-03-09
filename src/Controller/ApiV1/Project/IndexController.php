<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Security\Api\Progress\ProjectVoter;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class IndexController extends AbstractController
{
    #[Route('/api/v1/project/{id}', name: 'api.v1.project.index', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['GET'])]
    public function index(
        string            $id,
        ProjectRepository $projectRepository,
    ): JsonResponse {
        $project = $projectRepository->get(Ulid::fromString($id));

        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->json([
            'success' => true,
            'project' => [
                'id' => $project->getId()->toRfc4122(),
                'title' => $project->getTitle(),
                'description' => $project->getDescription(),
                'color' => $project->getColor(),
            ],
        ]);
    }
}
