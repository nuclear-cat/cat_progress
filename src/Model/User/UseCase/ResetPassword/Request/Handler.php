<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Request;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\ResetPasswordRequest;
use App\Model\User\Repository\ChangePasswordRequestRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordResetRequestSender;
use App\Model\User\Service\PasswordResetTokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private UserRepository                  $users,
        private ChangePasswordRequestRepository $resetTokens,
        private PasswordResetTokenGenerator     $tokenGenerator,
        private PasswordResetRequestSender      $tokenSender,
        private EntityManagerInterface          $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->email));

        if (!$user) {
            throw new \DomainException("User with email {$command->email} not found.");
        }

        $resetToken = ResetPasswordRequest::createNew($this->tokenGenerator->generate(), new \DateTimeImmutable('+12 hours'), $user);
        $this->resetTokens->add($resetToken);
        $this->entityManager->flush();

        $this->tokenSender->send($user->getEmail(), $resetToken);
    }
}
