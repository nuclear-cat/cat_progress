<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Profile;

use App\Model\User\Repository\UserRepository;
use App\Model\User\UseCase\ChangeAvatar\Command;
use App\Model\User\UseCase\ChangeAvatar\Handler;
use App\Security\UserIdentity;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class ChangeAvatarController extends AbstractController
{
    #[Route('/api/v1/profile/change_avatar', name: 'api.v1.profile.change_avatar', methods: ['POST'])]
    public function changeAvatar(
        UserRepository $userRepository,
        Request        $request,
        Handler        $handler,
        FilterService $filterService,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));

        /** @var UploadedFile|null $image */
        $image = $request->files->get('image');

        if (!$image) {
            throw new BadRequestHttpException('No image.');
        }

        $command = new Command(
            userId: $user->getId(),
            imagePath: $image->getRealPath(),
            cropXPosition: (int)$request->request->get('image_position')['x1'] ?? throw new BadRequestHttpException(),
            cropYPosition: (int)$request->request->get('image_position')['y1'] ?? throw new BadRequestHttpException(),
            cropWidth: (int)$request->request->get('width') ?? throw new BadRequestHttpException(),
            cropHeight: (int)$request->request->get('height') ?? throw new BadRequestHttpException(),
        );

        $imagePath = $handler->handle($command);

        return $this->json([
            'success' => true,
            'avatar_src' => $filterService->getUrlOfFilteredImage($imagePath, 'profile_page_avatar'),
        ]);
    }
}
