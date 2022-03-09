<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project;

use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Project\Delete\Command;
use App\Model\Progress\UseCase\Project\Delete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class DeleteController extends AbstractController
{
    #[Route('/api/v1/project/{id}/delete', name: 'api.v1.project.delete', methods: ['POST'])]
    public function delete(
        string            $id,
        Handler           $handler,
        UserRepository    $userRepository,
        ProjectRepository $projectRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $project = $projectRepository->getByIdAndCreator(Ulid::fromString($id), $user);

        $command = new Command(
            id: $project->getId(),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
