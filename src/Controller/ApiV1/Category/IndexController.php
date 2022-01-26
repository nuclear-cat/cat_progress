<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\UserRepository;
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
    #[Route('/api/v1/category/{id}', name: 'api.v1.category.index', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['GET'])]
    public function index(
        string             $id,
        UserRepository     $userRepository,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $category = $categoryRepository->getByIdAndUser(Ulid::fromString($id), $user);

        return $this->json([
            'success' => true,
            'category' => [
                'id' => $category->getId()->toRfc4122(),
                'title' => $category->getTitle(),
                'description' => $category->getDescription(),
                'color' => $category->getColor(),
            ],
        ]);
    }
}
