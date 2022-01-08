<?php

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\Repository\HabitRepository;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;


/**
 * @method UserIdentity getUser()
 */
class InfoController extends AbstractController
{
    #[Route('/cabinet/habit/{id}/info', name: 'cabinet.habit.show', methods: ['GET'])]
    public function info(
        string $id,
        HabitRepository $habitRepository,
    ): Response {
        $habit = $habitRepository->get(Ulid::fromString($id));

        return $this->render('cabinet/habit/show.html.twig', [
            'habit' => $habit,
            'weekdays' => Weekday::cases(),
        ]);
    }
}