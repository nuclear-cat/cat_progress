<?php declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\Mailer;
use App\Model\User\Vo\Email;
use App\Model\User\Entity\EmailChangeRequest;

class EmailChangeRequestSender
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Email $email, EmailChangeRequest $request): void
    {
        $this->mailer->send($email->getValue(), 'Change email reques', 'mail/user/change_email_request.html.twig', [
            'token' => $request->getToken(),
        ]);
    }
}
