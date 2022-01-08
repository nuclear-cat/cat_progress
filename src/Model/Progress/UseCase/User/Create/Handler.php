<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\User\Create;

use App\Model\Progress\Entity\User;
use App\Model\Progress\Repository\UserRepository;
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
        $user = new User(
            $command->id,
            $command->name,
        );

        $this->userRepository->add($user);
        $this->entityManager->flush();
    }
}
