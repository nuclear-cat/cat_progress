<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Annotation\Uuid as UuidAnnotation;
use App\Model\Progress\Entity\HabitCompletionType;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Habit\Complete\Command;
use App\Model\Progress\UseCase\Habit\Complete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CompleteController extends AbstractController
{
    #[Route('/api/v1/habit/{id}/complete', name: 'api.v1.habit.complete', requirements: ['id' => UuidAnnotation::PATTERN], methods: ['POST'])]
    public function complete(
        string          $id,
        Request         $request,
        Handler         $handler,
        UserRepository  $userRepository,
        HabitRepository $habitRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $habit = $habitRepository->getByIdAndUser(Ulid::fromString($id), $user);

        $command = new Command(
            habitId: $habit->getId(),
            completedAt: new \DateTimeImmutable($data['completed_at']),
            type: HabitCompletionType::from($data['completion_type']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
