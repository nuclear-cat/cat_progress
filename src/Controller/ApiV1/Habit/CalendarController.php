<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Habit;

use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\HabitCompletion;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\Service\Calendar\CalendarService;
use App\Security\UserIdentity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class CalendarController extends AbstractController
{
    #[Route('/api/v1/habit/calendar/{date}', name: 'api.v1.habit.calendar', methods: ['GET'])]
    #[ParamConverter('date', options: ['format' => '!Y-m-d'])]
    public function calendar(
        UserRepository     $userRepository,
        HabitRepository    $habitRepository,
        CalendarService    $calendarService,
        \DateTimeImmutable $date,
    ): JsonResponse {


        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $calendarDates = $calendarService->getCalendarDates($date, $this->getUser()->getTimezone());

        $habits = $habitRepository->getForCalendar(
            $user,
            ($calendarDates->getFirstDate())
//                ->setTimezone($this->getUser()->getTimezone())
                ->setTime(0, 0, 0),
            ($calendarDates->getLastDate())
//                ->setTimezone($this->getUser()->getTimezone())
                ->setTime(23, 59, 59)
        );

        return $this->json([
            'success' => true,
            'weeks' => array_map(function (array $week) use ($habits): array {
                return array_map(function (\DateTimeImmutable $date) use ($habits): array {
                    return [
                        'date' => $date->format(\DateTimeInterface::RFC3339_EXTENDED),
                        'habits' => array_values(array_map(function (Habit $habit) use ($date) {
                            return [
                                'id' => $habit->getId()->toRfc4122(),
                                'title' => $habit->getTitle(),
                                'description' => $habit->getDescription(),
                                'completions' => array_map(function (HabitCompletion $completion) {
                                    return [
                                        'id' => $completion->getId()->toRfc4122(),
                                        'completed_at' => $completion->getCompletedAt()->format(\DateTimeInterface::RFC3339_EXTENDED),
                                    ];
                                }, $habit->getDayCompletions($date)),
                                'category' => $habit->getCategory() ? [
                                    'id' => $habit->getCategory()->getId()->toRfc4122(),
                                    'title' => $habit->getCategory()->getTitle(),
                                    'color' => $habit->getCategory()->getColor()->value,
                                ] : null,
                            ];
                        }, array_filter($habits, function (Habit $habit) use ($date) {
                            return $habit->isActual($date, $this->getUser()->getTimezone());
                        }))),
                    ];
                }, $week);
            }, $calendarDates->getDates()),
        ]);
    }
}
