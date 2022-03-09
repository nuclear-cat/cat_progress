<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Profile;

use App\Model\User\Repository\UserRepository;
use App\Security\UserIdentity;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class IndexController extends AbstractController
{
    #[Route('/api/v1/profile', name: 'api.v1.profile.index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        FilterService  $filterService,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $avatarPublicDir = $this->getParameter('avatar_public_dir');

        return $this->json([
            'success' => true,
            'profile' => [
                'id' => $this->getUser()->getUserIdentifier(),
                'email' => $user->getEmail()->getValue(),
                'name' => $user->getName(),
                'avatar_src' => $user->getAvatarImage()
                    ? $filterService->getUrlOfFilteredImage(
                        $avatarPublicDir . $user->getAvatarImage(), 'profile_page_avatar'
                    )
                    : null,
            ]
        ]);
    }
}
