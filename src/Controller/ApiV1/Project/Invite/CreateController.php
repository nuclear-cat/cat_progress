<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project\Invite;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Entity\Project\Permission;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Project\CreateInviteByEmail\Command;
use App\Model\Progress\UseCase\Project\CreateInviteByEmail\Handler;
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
    #[Route(
        '/api/v1/project/{projectId}/invite/create',
        name: 'api.v1.project.invite.create',
        requirements: ['projectId' => UuidAnnotation::PATTERN],
        methods: ['POST'],
    )]
    public function create(
        Request           $request,
        string            $projectId,
        UserRepository    $userRepository,
        ProjectRepository $projectRepository,
        Handler           $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $project = $projectRepository->getByIdAndCreator(Ulid::fromString($projectId), $user);

        $command = new Command(
            projectId: $project->getId(),
            email: $data['email'],
            permissions: array_map(function (string $item): Permission {
                return Permission::from($item);
            }, $data['permissions']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
