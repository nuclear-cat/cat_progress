<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\UseCase\Habit\Update;
use App\Model\Progress\UseCase\Habit\Update\Command;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class EditController extends AbstractController
{
    #[Route('/cabinet/habit/{id}/edit', name: 'cabinet.habit.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request         $request,
        string          $id,
        HabitRepository $habitRepository,
        Update\Handler  $handler,
    ): Response {
        $habit = $habitRepository->get(Ulid::fromString($id));

        $command = new Command(
            id: $habit->getId(),
            title: $habit->getTitle(),
            totalPoints: $habit->getTotalPoints(),
            description: $habit->getDescription(),
            weekdays: $habit->getWeekdays(),
        );

        $form = $this
            ->createForm(Update\Form::class, $command)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('cabinet.habit.edit', ['id' => $id]);
        }

        return $this->render('cabinet/habit/edit.html.twig', [
            'habit' => $habit,
            'form' => $form->createView(),
        ]);
    }
}
