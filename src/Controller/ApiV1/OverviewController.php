<?php declare(strict_types=1);

namespace App\Controller\ApiV1;

use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\HabitCompletion;
use App\Model\Progress\Entity\Task;
use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\TaskRepository;
use App\Model\Progress\Repository\UserRepository;
use App\ReadModel\Habit\CurrentHabitDto;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class OverviewController extends AbstractController
{
    #[Route('/api/v1/overview', name: 'api.v1.overview', methods: ['GET'])]
    public function overview(
        Request         $request,
        TaskRepository  $taskRepository,
        UserRepository  $userRepository,
        HabitRepository $habitRepository,
    ): Response {
        $habitsDate = new \DateTimeImmutable($request->query->get('habits_date'));
        $habitsWeekday = Weekday::from($habitsDate->format('l'));

        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $activeTasks = $taskRepository->findActiveUserTasks($user);
        $completedTasks = $taskRepository->findCompletedUserTasks($user);

        $todayHabits = $habitRepository->getForCalendar(
            $user,
            $habitsDate->setTime(0, 0, 0),
            $habitsDate->setTime(23, 59, 59),
            $habitsWeekday,
        );

        return $this->json([
            'success' => true,
            'today_habits' => array_map(function (Habit $habit) {
                return [
                    'id' => $habit->getId()->toRfc4122(),
                    'title' => $habit->getTitle(),
                    'description' => $habit->getDescription(),
                    'completions' => array_map(function (HabitCompletion $completion) {
                        return [
                            'id' => $completion->getId()->toRfc4122(),
                            'completed_at' => $completion->getCompletedAt()->format(\DateTimeInterface::RFC3339_EXTENDED),
                        ];
                    }, $habit->getCompletions()),
                ];
            }, $todayHabits),
            'active_tasks' => array_map(function (Task $tasks) {
                return [
                    'id' => $tasks->getId()->toRfc4122(),
                    'title' => $tasks->getTitle(),
                    'description' => $tasks->getDescription(),
                    'completed_at' => $tasks->getCompletedAt()?->format(\DateTimeInterface::RFC3339_EXTENDED),
                ];
            }, $activeTasks),
            'completed_tasks' => array_map(function (Task $tasks) {
                return [
                    'id' => $tasks->getId()->toRfc4122(),
                    'title' => $tasks->getTitle(),
                    'description' => $tasks->getDescription(),
                    'completed_at' => $tasks->getCompletedAt()?->format(\DateTimeInterface::RFC3339_EXTENDED),
                ];
            }, $completedTasks),
        ]);
    }
}
