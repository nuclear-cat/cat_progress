<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\Entity\Category;
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
class ListController extends AbstractController
{
    #[Route('/api/v1/category/list', name: 'api.v1.category.list', methods: ['GET'])]
    public function list(
        UserRepository     $userRepository,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $categories = $categoryRepository->findByUser($user);

        return $this->json([
            'success' => true,
            'categories' => array_map(function (Category $category) {
                return [
                    'id' => $category->getId()->toRfc4122(),
                    'title' => $category->getTitle(),
                    'description' => $category->getDescription(),
                    'color' => $category->getColor()->value,
                ];
            }, $categories),
        ]);
    }
}
