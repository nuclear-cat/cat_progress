<?php declare(strict_types=1);

namespace App\Controller\Cabinet;

use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\Service\Calendar\CalendarService;
use App\ReadModel\Habit\CurrentHabitsFetcher;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method UserIdentity getUser()
 */
class HomeController extends AbstractController
{
    #[Route('/', name: 'cabinet.home', methods: ['GET'])]
    public function home(
        UserRepository       $userRepository,
        CategoryRepository   $categoryRepository,
        HabitRepository      $habitRepository,
        CurrentHabitsFetcher $currentHabitsFetcher,
        CalendarService      $calendarService,
    ): Response {
        $todayHabits = $currentHabitsFetcher->fetch(
            (new \DateTimeImmutable())
                ->setTimezone($this->getUser()->getTimezone()),
            $this->getUser()->getUserIdentifier(),
        );

        $habits = $habitRepository->findAll();

        return $this->render('cabinet/home.html.twig', [
            'today_habits' => $todayHabits,
            'habits' => $habits,
            'calendar' => $calendarService->getCalendarDates(new \DateTimeImmutable(), $this->getUser()->getTimezone()),
            'current_date' => (new \DateTimeImmutable())->setTime(0, 0, 0),
        ]);
    }
}
