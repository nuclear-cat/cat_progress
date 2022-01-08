<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Model\Progress\Entity\Habit;
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
class ListController extends AbstractController
{
    #[Route('/api/v1/habit/list', name: 'api.v1.habit.list', methods: ['GET'])]
    public function list(
        UserRepository  $userRepository,
        HabitRepository $habitRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $habits = $habitRepository->findByUser($user);

        return $this->json([
            'success' => true,
            'habits' => array_map(function(Habit $habit) {
                return [
                    'id' => $habit->getId()->toRfc4122(),
                    'title' => $habit->getTitle(),
                    'description' => $habit->getDescription(),
                    'weekdays' => array_map(function (HabitWeekday $habitWeekday) {
                        return $habitWeekday->getWeekday()->value;
                    }, $habit->getHabitWeekdays()),
                ];
            }, $habits),
        ]);
    }
}
