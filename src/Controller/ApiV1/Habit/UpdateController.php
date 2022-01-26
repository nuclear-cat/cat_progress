<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Habit\Update\Command;
use App\Model\Progress\UseCase\Habit\Update\Handler;
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
    #[Route('/api/v1/habit/{id}/update', name: 'api.v1.habit.update', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['POST'])]
    public function create(
        string  $id,
        Request $request,
        Handler $handler,
        UserRepository  $userRepository,
        HabitRepository $habitRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $habit = $habitRepository->getByIdAndUser(Ulid::fromString($id), $user);

        $command = new Command(
            id: $habit->getId(),
            categoryId: Ulid::fromString($data['category_id']),
            title: $data['title'],
            totalPoints: $data['points'],
            description: $data['description'],
            weekdays: array_map(function (string $item) {
                return Weekday::from($item);
            }, $data['weekdays']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
