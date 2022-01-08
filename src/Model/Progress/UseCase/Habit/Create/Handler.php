<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Create;

use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\HabitWeekday;
use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\HabitRepository;
use App\Model\Progress\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private HabitRepository $habitRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function handle(Command $command): Ulid
    {
        $user = $this->userRepository->get($command->userId);
        $category = $this->categoryRepository->get($command->categoryId);

        $habit = (new Habit(
            new Ulid(),
            $command->title,
            $category,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            $user,
        ))->setDescription($command->description);

        foreach ($command->weekdays as $weekday) {
            if (in_array($weekday, $habit->getWeekdays())) {
                continue;
            }

            $habitWeekday = new HabitWeekday(
                new Ulid(),
                $habit,
                $weekday,
            );

            $this->entityManager->persist($habitWeekday);

            $habit->addHabitWeekday($habitWeekday);
        }

        $this->habitRepository->add($habit);
        $this->entityManager->flush();

        return $habit->getId();
    }
}
