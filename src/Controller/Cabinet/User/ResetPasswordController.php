<?php declare(strict_types=1);

namespace App\Controller\Cabinet\User;

use App\Model\User\Repository\ChangePasswordRequestRepository;
use App\Model\User\UseCase\ResetPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @see Route
 */
class ResetPasswordController extends AbstractController
{
    #[Route('/cabinet/reset_password', name: 'cabinet.reset_password', methods: ['GET', 'POST'])]
    public function request(Request $request, ResetPassword\Request\Handler $handler): Response
    {
        $command = new ResetPassword\Request\Command();
        $form = $this->createForm(ResetPassword\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('notice', 'Check your email');

                return $this->redirectToRoute('cabinet.reset_password.confirm_info', [
                    'email' => $command->email,
                ]);
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());

                return $this->redirectToRoute('cabinet.reset_password');
            }
        }

        return $this->render('cabinet/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cabinet/reset_password/confirm_info/{email}', name: 'cabinet.reset_password.confirm_info', methods: ['GET'])]
    public function confirmInfo(string $email): Response
    {
        return $this->render('cabinet/reset_password_confirm_info.html.twig', [
            'email' => $email
        ]);
    }

    #[Route('/cabinet/reset_password/{token}/confirm', name: 'cabinet.reset_password.confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, string $token, ResetPassword\Confirm\Handler $handler, ChangePasswordRequestRepository $resetTokens): Response
    {
        $resetToken = $resetTokens->findByToken($token);

        if (!$resetToken) {
            $this->addFlash('error', 'Incorrect token.');

            return $this->redirectToRoute('home');
        }

        $command = new ResetPassword\Confirm\Command();
        $form = $this
            ->createForm(ResetPassword\Confirm\Form::class, $command)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command, $resetToken);
                $this->addFlash('notice', 'Password changed');

                return $this->redirectToRoute('cabinet.login');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());

                return $this->redirectToRoute('auth.password_reset.confirm', ['token' => $token]);
            }
        }

        return $this->render('cabinet/reset_password_confirm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
