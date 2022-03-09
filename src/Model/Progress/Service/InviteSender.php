<?php declare(strict_types=1);

namespace App\Model\Progress\Service;

use App\Model\Mailer;
use App\Model\Progress\Entity\Project\Invite;
use App\Model\Progress\ValueObject\Email;

class InviteSender
{
    public function __construct(
        private Mailer $mailer,
        private string $frontendUrl,
    ) {
    }

    public function send(Email $email, Invite $invite): void
    {
        $this->mailer->send($email->getValue(), 'Invitation to the project', 'mail/progress/invite.html.twig', [
            'invite' => $invite,
            'invite_url' => "$this->frontendUrl/project/{$invite->getProject()->getId()->toRfc4122()}/invite/{$invite->getId()->toRfc4122()}/confirm/{$invite->getToken()}#{$email->getValue()}",
        ]);
    }
}
