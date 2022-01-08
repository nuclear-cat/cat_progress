<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Update;

use App\Model\Progress\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private TaskRepository         $taskRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $task = $this->taskRepository->get($command->taskId);

        $task
            ->setTitle($command->title)
            ->setDescription($command->description);

        $this->entityManager->flush();
    }
}
