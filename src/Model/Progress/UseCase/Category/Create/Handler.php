<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Category\Create;

use App\Model\Progress\Entity\Category;
use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function handle(Command $command): Ulid
    {
        $user = $this->userRepository->get($command->userId);

        $habit = (new Category(
            new Ulid(),
            $command->title,
            $user,
        ))
            ->setDescription($command->description)
            ->setColor($command->color);

        $this->categoryRepository->add($habit);
        $this->entityManager->flush();

        return $habit->getId();
    }
}
