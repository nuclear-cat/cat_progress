<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\Entity\Category;
use App\Model\Progress\Entity\CategoryColor;
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
class ColorListController extends AbstractController
{
    #[Route('/api/v1/category/color/list', name: 'api.v1.category.color.list', methods: ['GET'])]
    public function list(): JsonResponse {
        return $this->json([
            'success' => true,
            'colors' => array_map(function(CategoryColor $color) {
                return $color->value;
            }, CategoryColor::cases()),
        ]);
    }
}
