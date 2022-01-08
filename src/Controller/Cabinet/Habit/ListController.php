<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\Repository\HabitRepository;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method UserIdentity getUser()
 */
class ListController extends AbstractController
{
    #[Route('/cabinet/habit/list', name: 'cabinet.habit.list', methods: ['GET'])]
    public function edit(
        HabitRepository $habitRepository,
    ): Response {
        $habits = $habitRepository->findAll();

        return $this->render('cabinet/habit/list.html.twig', [
            'habits' => $habits,
        ]);
    }
}
