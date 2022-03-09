<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Register\Request;

use App\Event\UserCreatedEvent;
use App\Model\User\Entity\ConfirmRequest;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
use App\Model\User\Repository\ConfirmRequestRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\ConfirmRequestSender;
use App\Model\User\Service\ConfirmTokenGenerator;
use App\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private UserRepository           $userRepository,
        private ConfirmRequestRepository $confirmRequestRepository,
        private EntityManagerInterface   $entityManager,
        private PasswordHasher           $hasher,
        private ConfirmRequestSender     $tokenSender,
        private ConfirmTokenGenerator    $tokenGenerator,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function handle(Command $command): Ulid
    {
        $email = new Email($command->email);
        $id = new Ulid();

        if ($this->userRepository->hasByEmail($email)) {
            throw new \DomainException('User with this email already exists.');
        }

        $token = $this->tokenGenerator->generate();
        $user = User::signUpByEmail(
            new Ulid(),
            $email,
            $command->name,
            $this->hasher->hash($command->password),
            new \DateTimeZone($command->timezone),
        );

        $confirmRequest = new ConfirmRequest($token, $user);

        $this->tokenSender->send($user->getId(), $email, $confirmRequest, $command->target);

        $this->userRepository->add($user);
        $this->confirmRequestRepository->add($confirmRequest);
        $this->entityManager->flush();
        $this->dispatcher->dispatch(new UserCreatedEvent($user->getId(), $user->getName()));

        return $id;
    }
}
