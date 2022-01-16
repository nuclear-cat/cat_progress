<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\Entity\CategoryColor;
use App\Model\Progress\UseCase\Category\ChangeColor\Command;
use App\Model\Progress\UseCase\Category\ChangeColor\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class ChangeColorController extends AbstractController
{
    #[Route('/api/v1/category/{id}/change_color', name: 'api.v1.category.change_color', methods: ['POST'])]
    public function create(
        string  $id,
        Request $request,
        Handler $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Command(
            id: Ulid::fromString($id),
            color: CategoryColor::from($data['color']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
