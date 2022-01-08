<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\Entity\CategoryColor;
use App\Model\Progress\UseCase\Category\Update\Command;
use App\Model\Progress\UseCase\Category\Update\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class UpdateController extends AbstractController
{
    #[Route('/api/v1/category/{id}/update', name: 'api.v1.category.update', methods: ['POST'])]
    public function create(
        string  $id,
        Request $request,
        Handler $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Command(
            id: Ulid::fromString($id),
            title: $data['title'],
            description: $data['description'],
            color: CategoryColor::from($data['color']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
