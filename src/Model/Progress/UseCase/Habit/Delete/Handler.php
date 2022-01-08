<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Delete;

use App\Model\Progress\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private HabitRepository        $habitRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $habit = $this->habitRepository->get($command->id);

        $this->habitRepository->remove($habit);
        $this->entityManager->flush();
    }
}
