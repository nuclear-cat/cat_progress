<?php declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\Mailer;
use App\Model\User\Entity\ConfirmRequest;
use App\Model\User\Entity\Email;

class ConfirmRequestSender
{
    public function __construct(private Mailer $mailer)
    {
    }

    public function send($id, Email $email, ConfirmRequest $request): void
    {
        $this->mailer->send($email->getValue(), 'Confirm E-Mail', 'mail/user/signup.html.twig', [
            'id' => $id,
            'token' => $request->getToken(),
        ]);
    }
}
