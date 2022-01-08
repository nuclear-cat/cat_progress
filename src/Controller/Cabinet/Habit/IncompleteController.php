<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\Repository\HabitCompletionRepository;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\UseCase\Habit\Incomplete\Command;
use App\Model\Progress\UseCase\Habit\Incomplete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class IncompleteController extends AbstractController
{
    #[Route('/cabinet/habit/{id}/{completionId}/incomplete', name: 'cabinet.habit.incomplete', methods: ['GET'])]
    public function complete(
        Request                   $request,
        string                    $id,
        string                    $completionId,
        HabitRepository           $habitRepository,
        HabitCompletionRepository $completionRepository,
        Handler                   $handler,
    ) {
        $habit = $habitRepository->get(Ulid::fromString($id));
        $completion = $completionRepository->get(Ulid::fromString($completionId));

        $command = new Command(
            habitId: $habit->getId(),
            completionId: $completion->getId(),
        );

        $handler->handle($command);

        if ($request->query->has('redirect')) {
            return $this->redirect($request->query->get('redirect'));
        }

        return $this->redirectToRoute('cabinet.home');
    }
}
