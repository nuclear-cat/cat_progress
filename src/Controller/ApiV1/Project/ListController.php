<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project;

use App\Model\Progress\Entity\Project\Project;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class ListController extends AbstractController
{
    #[Route('/api/v1/project/list', name: 'api.v1.project.list', methods: ['GET'])]
    public function list(
        UserRepository    $userRepository,
        ProjectRepository $projectRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $projects = $projectRepository->findByCreatorOrMember($user);

        return $this->json([
            'success' => true,
            'projects' => array_map(function (Project $project): array {
                return [
                    'id' => $project->getId()->toRfc4122(),
                    'title' => $project->getTitle(),
                    'description' => $project->getDescription(),
                    'color' => $project->getColor()->value,
                ];
            }, $projects),
        ]);
    }
}
