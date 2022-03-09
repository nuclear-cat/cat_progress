<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Profile;

use App\Model\User\Repository\UserRepository;
use App\Model\User\UseCase\Update\Command;
use App\Model\User\UseCase\Update\Handler;
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
class UpdateController extends AbstractController
{
    #[Route('/api/v1/profile/update', name: 'api.v1.profile.update', methods: ['POST'])]
    public function changeAvatar(
        UserRepository $userRepository,
        Request        $request,
        Handler        $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));

        $command = new Command(
            name: $data['name'] ?? throw new BadRequestHttpException('No name'),
            userId: $user->getId(),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
