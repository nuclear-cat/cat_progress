<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Update;

use App\Model\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->get($command->userId);
        $user->setName($command->name);

        $this->entityManager->flush();
    }
}
