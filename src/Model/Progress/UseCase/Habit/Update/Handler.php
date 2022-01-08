<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Update;

use App\Model\Progress\Entity\HabitWeekday;
use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private HabitRepository        $habitRepository,
        private CategoryRepository     $categoryRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $habit = $this->habitRepository->get($command->id);
        $category = $this->categoryRepository->get($command->categoryId);

        $habit
            ->setCategory($category)
            ->setDescription($command->description)
            ->setTotalPoints($command->totalPoints)
            ->setTitle($command->title);

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

        foreach ($habit->getHabitWeekdays() as $habitWeekday) {
            if (!in_array($habitWeekday->getWeekday(), $command->weekdays)) {
                $this->entityManager->remove($habitWeekday);
            }
        }

        $this->entityManager->flush();
    }
}
