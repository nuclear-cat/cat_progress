<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ChangeEmail\Request;

use App\Model\Flusher;
use App\Model\User\Entity\ChangeEmailRequest;
use App\Model\User\Repository\EmailChangeRequestRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\ConfirmTokenGenerator;
use App\Model\User\Service\EmailChangeRequestSender;
use App\Model\User\Vo\Email;
use Symfony\Component\Uid\Ulid;

class Handler
{
    private UserRepository               $users;
    private EmailChangeRequestRepository $emailChangeRequests;
    private ConfirmTokenGenerator        $tokenGenerator;
    private EmailChangeRequestSender     $sender;
    private Flusher                      $flusher;

    public function __construct(
        UserRepository $users,
        EmailChangeRequestRepository $emailChangeRequests,
        ConfirmTokenGenerator $tokenGenerator,
        EmailChangeRequestSender $sender,
        Flusher $flusher
    )
    {
        $this->users               = $users;
        $this->emailChangeRequests = $emailChangeRequests;
        $this->tokenGenerator      = $tokenGenerator;
        $this->sender              = $sender;
        $this->flusher             = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get($command->userId);
        $expiresAt = new \DateTimeImmutable('+6 hours');
        $now = new \DateTimeImmutable();

        $request = ChangeEmailRequest::create($this->tokenGenerator->generate(), $user, $expiresAt, $now);
        $this->emailChangeRequests->add($request);
        $this->flusher->flush();

        $this->sender->send(new Email($command->email), $request);
    }
}
