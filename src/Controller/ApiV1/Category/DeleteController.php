<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\UseCase\Category\Delete\Command;
use App\Model\Progress\UseCase\Category\Delete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class DeleteController extends AbstractController
{
    #[Route('/api/v1/category/{id}/delete', name: 'api.v1.category.delete', methods: ['POST'])]
    public function delete(
        string  $id,
        Handler $handler,
    ): JsonResponse {
        $command = new Command(
            id: Ulid::fromString($id),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
