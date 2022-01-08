<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Category\Update;

use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private CategoryRepository     $categoryRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $category = $this->categoryRepository->get($command->id);

        $category
            ->setTitle($command->title)
            ->setColor($command->color)
            ->setDescription($command->description);

        $this->entityManager->flush();
    }
}
