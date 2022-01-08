<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\Service\Calendar\CalendarService;
use App\Model\User;
use App\ReadModel\Habit\CurrentHabitsFetcher;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CalendarController extends AbstractController
{
    #[Route('/cabinet/habit/calendar', name: 'cabinet.habit.calendar', methods: ['GET'])]
    public function home(
        UserRepository       $userRepository,
        HabitRepository      $habitRepository,
        CurrentHabitsFetcher $currentHabitsFetcher,
        CalendarService      $calendarService,
    ): Response {
        $calendarDates = $calendarService->getCalendarDates(new \DateTimeImmutable(), $this->getUser()->getTimezone());

        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));

        $todayHabits = $currentHabitsFetcher->fetch(
            (new \DateTimeImmutable())
                ->setTimezone($this->getUser()->getTimezone()),
            $this->getUser()->getUserIdentifier(),
        );

        $habits = $habitRepository->getForCalendar(
            $user,
            ($calendarDates->getFirstDate())
                ->setTimezone($this->getUser()->getTimezone())
                ->setTime(0, 0, 0),
            ($calendarDates->getLastDate())
                ->setTimezone($this->getUser()->getTimezone())
                ->setTime(23, 59, 59)
        );

        return $this->render('cabinet/habit/calendar.html.twig', [
            'timezone' => $this->getUser()->getTimezone(),
            'today_habits' => $todayHabits,
            'habits' => $habits,
            'weekdays' => Weekday::cases(),
            'calendar' => $calendarDates->getDates(),
            'current_date' => (new \DateTimeImmutable())
                ->setTimezone($this->getUser()->getTimezone())
                ->setTime(0, 0, 0),
        ]);
    }
}
