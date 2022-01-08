<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Confirm;

use App\Model\User\Entity\ResetPasswordRequest;
use App\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private PasswordHasher $hasher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command, ResetPasswordRequest $token): void
    {
        if ($command->password !== $command->repeatPassword) {
            throw new \DomainException('Passwords don\'t match.');
        }

        if ($token->isExpired(new \DateTimeImmutable())) {
            throw new \DomainException('Token is expired.');
        }

        $user = $token->getUser();
        $passwordHash = $this->hasher->hash($command->password);
        $user->setPasswordHash($passwordHash);
        $token->setExpiredAt(new \DateTimeImmutable('-12 hour'));

        $this->entityManager->flush();
    }
}
