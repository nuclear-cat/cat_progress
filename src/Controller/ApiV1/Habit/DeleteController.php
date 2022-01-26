<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Habit\Delete\Command;
use App\Model\Progress\UseCase\Habit\Delete\Handler;
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
    #[Route('/api/v1/habit/{id}/delete', name: 'api.v1.habit.delete', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['POST'])]
    public function delete(
        string  $id,
        Handler $handler,
        UserRepository  $userRepository,
        HabitRepository $habitRepository,
    ): JsonResponse {
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $habit = $habitRepository->getByIdAndUser(Ulid::fromString($id), $user);

        $command = new Command(
            id: $habit->getId(),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
