<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project\Invite;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Repository\Project\InviteRepository;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Project\ConfirmInvite\Command;
use App\Model\Progress\UseCase\Project\ConfirmInvite\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class ConfirmController extends AbstractController
{
    #[Route(
        '/api/v1/project/{projectId}/invite/{inviteId}/confirm',
        name: 'api.v1.project.invite.confirm',
        requirements: [
            'projectId' => UuidAnnotation::PATTERN,
            'inviteId' => UuidAnnotation::PATTERN,
        ],
        methods: ['POST'],
    )]
    public function confirm(
        Request           $request,
        string            $projectId,
        string            $inviteId,
        UserRepository    $userRepository,
        ProjectRepository $projectRepository,
        InviteRepository  $inviteRepository,
        Handler           $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $invite = $inviteRepository->getByIdAndToken(Ulid::fromString($inviteId), $data['token']);
        $project = $projectRepository->get(Ulid::fromString($projectId));

        if ($invite->getProject() !== $project) {
            throw new BadRequestHttpException('Invalid invite project.');
        }

        $command = new Command(
            inviteId: $invite->getId(),
            projectId: $project->getId(),
            userId: $user->getId(),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
