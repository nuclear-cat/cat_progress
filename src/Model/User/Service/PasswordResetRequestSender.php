<?php declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\Mailer;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\ResetPasswordRequest;

class PasswordResetRequestSender
{
    public function __construct(
        private Mailer $mailer,
    ) {
    }

    public function send(Email $email, ResetPasswordRequest $request): void
    {
        $this->mailer->send($email->getValue(), 'Recover password', 'mail/reset_password.html.twig', [
            'token' => $request->getToken(),
        ]);
    }
}
