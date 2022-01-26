<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Complete;

use App\Model\Progress\Entity\HabitCompletion;
use App\Model\Progress\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private HabitRepository        $habitRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function handle(Command $command): void
    {
        $habit = $this->habitRepository->get($command->habitId);

        $completion = (new HabitCompletion(
            new Ulid(),
            $habit,
            new \DateTimeImmutable(),
            $command->completedAt,
        ))
            ->setTotalPoints($habit->getTotalPoints())
            ->setType($command->type);

        $this->entityManager->persist($completion);
        $this->entityManager->flush();
    }
}
