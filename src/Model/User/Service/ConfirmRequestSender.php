<?php declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\Mailer;
use App\Model\User\Entity\ConfirmRequest;
use App\Model\User\Entity\Email;
use Symfony\Component\Uid\Ulid;

class ConfirmRequestSender
{
    public function __construct(
        private Mailer $mailer,
        private string $frontendUrl,
    ) {
    }

    public function send(Ulid $id, Email $email, ConfirmRequest $request, ?string $target): void
    {
        $this->mailer->send($email->getValue(), 'Confirm E-Mail', 'mail/user/signup.html.twig', [
            'id' => $id,
            'token' => $request->getToken(),
            'registration_confirm_url' => "$this->frontendUrl/registration-confirm/{$id->toRfc4122()}/{$request->getToken()}#$target",
        ]);
    }
}
