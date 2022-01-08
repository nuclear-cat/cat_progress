<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Incomplete;

use App\Model\Progress\Repository\HabitCompletionRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private HabitCompletionRepository $habitCompletionRepository,
        private EntityManagerInterface    $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $completion = $this->habitCompletionRepository->get($command->completionId);

        $this->entityManager->remove($completion);
        $this->entityManager->flush();
    }
}
