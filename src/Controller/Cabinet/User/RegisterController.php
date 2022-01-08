<?php declare(strict_types=1);

namespace App\Controller\Cabinet\User;

use App\Model\User\UseCase\Register;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class RegisterController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/cabinet/register', name: 'cabinet.register', methods: ['GET', 'POST'])]
    public function request(Request $request, Register\Request\Handler $handler): Response
    {
        $command = new Register\Request\Command();

        $form = $this->createForm(Register\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('notice', 'Check your email.');

                return $this->redirectToRoute('cabinet.register.confirm_info', ['email' => $command->email]);
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('cabinet/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/cabinet/register/confirm_info/{email}', name: 'cabinet.register.confirm_info', methods: ['GET'])]
    public function confirmInfo(string $email): Response
    {
        return $this->render('cabinet/confirm_info.html.twig', [
            'email' => $email
        ]);
    }

    #[Route('/cabinet/register/{id}/{token}/confirm', name: 'cabinet.register.confirm', methods: ['GET'])]
    public function confirm(string $id, string $token, Register\Confirm\Handler $handler): Response
    {
        $command = new Register\Confirm\Command(Ulid::fromString($id), $token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email confirmed.');

            return $this->redirectToRoute('cabinet.login');
        } catch (\DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('home');
        }
    }
}
