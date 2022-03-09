<?php declare(strict_types=1);

namespace App\Controller\ApiV1;

use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\HabitCompletion;
use App\Model\Progress\Entity\Project\Project;
use App\Model\Progress\Entity\Task;
use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\TaskRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\User\Entity\User;
use App\Security\UserIdentity;
use Liip\ImagineBundle\Service\FilterService;
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
        Request                                   $request,
        TaskRepository                            $taskRepository,
        UserRepository                            $userRepository,
        \App\Model\User\Repository\UserRepository $authUserRepository,
        HabitRepository                           $habitRepository,
        ProjectRepository                         $projectRepository,
        FilterService                             $filterService,
    ): Response {
        $habitsDate = new \DateTimeImmutable($request->query->get('habits_date'));
        $habitsWeekday = Weekday::from($habitsDate->format('l'));
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $taskProject = null;

        if ($request->query->get('task_project_id')) {
            $taskProjectId = $request->query->get('task_project_id');
            $taskProject = $projectRepository->get(Ulid::fromString($taskProjectId));
            $this->denyAccessUnlessGranted('view', $taskProject);
        }

        $activeTasks = $taskRepository->findActiveUserTasks($user, $taskProject);
        $activeTasksUserIds = array_unique(array_map(function (Task $task): Ulid {
            return $task->getUser()->getId();
        }, $activeTasks));
        $activeTasksAuthUsers = $authUserRepository->findByIds($activeTasksUserIds);

        $completedTasks = $taskRepository->findCompletedUserTasks($user);
        $projects = $projectRepository->findByCreatorOrMember($user);

        $todayHabits = $habitRepository->getForCalendar(
            $user,
            $habitsDate->setTime(0, 0, 0),
            $habitsDate->setTime(23, 59, 59),
            $habitsWeekday,
        );

        return $this->json([
            'success' => true,
            'projects' => array_map(function (Project $project) {
                return [
                    'id' => $project->getId()->toRfc4122(),
                    'title' => $project->getTitle(),
                    'description' => $project->getDescription(),
                    'color' => $project->getColor()->value,
                ];
            }, $projects),
            'today_habits' => array_map(function (Habit $habit) {
                return [
                    'id' => $habit->getId()->toRfc4122(),
                    'title' => $habit->getTitle(),
                    'description' => $habit->getDescription(),
                    'completions' => array_map(function (HabitCompletion $completion) {
                        return [
                            'id' => $completion->getId()->toRfc4122(),
                            'completed_at' => $completion->getCompletedAt()->format(\DateTimeInterface::RFC3339_EXTENDED),
                            'type' => $completion->getType()->value,
                        ];
                    }, $habit->getCompletions()),
                ];
            }, $todayHabits),
            'active_tasks' => array_map(function (Task $task) use ($activeTasksAuthUsers, $filterService): array {
                /** @var User $authUser */
                $authUser = current(array_filter($activeTasksAuthUsers, function (User $authUser) use ($task): bool {
                    return $authUser->getId()->toRfc4122() === $task->getUser()->getId()->toRfc4122();
                }));
                $avatarPublicDir = $this->getParameter('avatar_public_dir');

                return [
                    'id' => $task->getId()->toRfc4122(),
                    'title' => $task->getTitle(),
                    'description' => $task->getDescription(),
                    'completed_at' => $task->getCompletedAt()?->format(\DateTimeInterface::RFC3339_EXTENDED),
                    'project' => $task->getProject() ? [
                        'id' => $task->getProject()->getId()->toRfc4122(),
                        'title' => $task->getProject()->getTitle(),
                        'description' => $task->getProject()->getDescription(),
                        'color' => $task->getProject()->getColor()->value,
                    ] : null,
                    'created_at' => $task->getCreatedAt()->format(\DateTimeInterface::RFC3339_EXTENDED),
                    'creator' => [
                        'id' => $authUser->getId()->toRfc4122(),
                        'name' => $authUser->getName(),
                        'avatar_src' => $authUser->getAvatarImage()
                            ? $filterService->getUrlOfFilteredImage(
                                $avatarPublicDir . $authUser->getAvatarImage(),
                                'task_profile'
                            )
                            : null,
                    ],
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
