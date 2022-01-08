<?php declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    private MailerInterface $mailer;
    private string $fromEmail;
    private string $fromName;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, string $fromEmail, string $fromName, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->twig = $twig;
    }

    public function send(string $emailAddress, string $subject, string $template, array $parameters): void
    {
        $email = (new Email())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to($emailAddress)
            ->subject($subject)
            ->html($this->twig->render($template, $parameters));

        $this->mailer->send($email);
    }
}
