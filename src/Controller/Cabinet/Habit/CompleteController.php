<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\UseCase\Habit\Complete\Command;
use App\Model\Progress\UseCase\Habit\Complete\Handler;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CompleteController extends AbstractController
{
    #[Route('/cabinet/habit/{id}/{date}/complete', name: 'cabinet.habit.complete', methods: ['GET'])]
    public function complete(
        Request         $request,
        string          $id,
        string          $date,
        HabitRepository $habitRepository,
        Handler         $handler,
    ) {
        $habit = $habitRepository->get(Ulid::fromString($id));

        $command = new Command(
            habitId: $habit->getId(),
            completedAt: new \DateTimeImmutable($date),
        );

        $handler->handle($command);

        if ($request->query->has('redirect')) {
            return $this->redirect($request->query->get('redirect'));
        }

        return $this->redirectToRoute('cabinet.home');
    }
}
