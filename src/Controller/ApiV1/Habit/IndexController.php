<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Entity\HabitWeekday;
use App\Model\Progress\Repository\HabitRepository;
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
    #[Route('/api/v1/habit/{id}', name: 'api.v1.habit.index', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['GET'])]
    public function index(
        string          $id,
        UserRepository  $userRepository,
        HabitRepository $habitRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $habit = $habitRepository->getByIdAndUser(Ulid::fromString($id), $user);

        return $this->json([
            'success' => true,
            'habit' => [
                'id' => $habit->getId()->toRfc4122(),
                'title' => $habit->getTitle(),
                'description' => $habit->getDescription(),
                'points' => $habit->getTotalPoints(),
                'weekdays' => array_map(function (HabitWeekday $habitWeekday) {
                    return $habitWeekday->getWeekday()->value;
                }, $habit->getHabitWeekdays()),
                'category' => [
                    'id' => $habit->getCategory()->getId()->toRfc4122(),
                    'title' => $habit->getCategory()->getTitle(),
                    'description' => $habit->getCategory()->getDescription(),
                ],
            ],
        ]);
    }
}
