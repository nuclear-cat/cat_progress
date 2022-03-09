<?php declare(strict_types=1);

namespace App\Controller\ApiV1;

use App\Model\Progress\ValueObject\Color;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method UserIdentity getUser()
 */
class ColorListController extends AbstractController
{
    #[Route('/api/v1/color/list', name: 'api.v1.color.list', methods: ['GET'])]
    public function list(): JsonResponse {
        return $this->json([
            'success' => true,
            'colors' => array_map(function(Color $color): string {
                return $color->value;
            }, Color::cases()),
        ]);
    }
}
