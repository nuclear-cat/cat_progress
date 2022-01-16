<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Category\ChangeColor;

use App\Model\Progress\Repository\CategoryRepository;
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
            ->setColor($command->color);

        $this->entityManager->flush();
    }
}
