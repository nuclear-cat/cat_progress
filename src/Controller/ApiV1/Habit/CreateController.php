<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\UseCase\Habit\Create\Command;
use App\Model\Progress\UseCase\Habit\Create\Handler;
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
    #[Route('/api/v1/habit/create', name: 'api.v1.habit.create', methods: ['POST'])]
    public function create(
        Request $request,
        Handler $handler,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Command();
        $command->title = $data['title'];
        $command->description = $data['description'];
        $command->weekdays = array_map(function (string $item) {
            return Weekday::from($item);
        }, $data['weekdays']);
        $command->categoryId = Ulid::fromString($data['category_id']);
        $command->userId = Ulid::fromString($this->getUser()->getUserIdentifier());

        $id = $handler->handle($command);

        return $this->json([
            'success' => true,
            'id' => $id->toRfc4122(),
        ]);
    }
}
