<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\Entity\CategoryColor;
use App\Model\Progress\UseCase\Category\Create\Command;
use App\Model\Progress\UseCase\Category\Create\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CreateController extends AbstractController
{
    #[Route('/api/v1/category/create', name: 'api.v1.category.create', methods: ['POST'])]
    public function create(
        Request $request,
        Handler $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Command();
        $command->title = $data['title'];
        $command->description = $data['description'];
        $command->userId = Ulid::fromString($this->getUser()->getUserIdentifier());
        $command->color = CategoryColor::from($data['color']);

        $id = $handler->handle($command);

        return $this->json([
            'success' => true,
            'id' => $id->toRfc4122(),
        ]);
    }
}
