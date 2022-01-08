<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Habit;

use App\Model\Progress\UseCase\Habit\Create;
use App\Model\Progress\UseCase\Habit\Create\Command;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method UserIdentity getUser()
 */
class CreateController extends AbstractController
{
    #[Route('/cabinet/habit/create', name: 'cabinet.habit.create', methods: ['GET', 'POST'])]
    public function edit(
        Request         $request,
        Create\Handler  $handler,
    ): Response {
        $command = new Command();

        $form = $this
            ->createForm(Create\Form::class, $command)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $handler->handle($command);

            return $this->redirectToRoute('cabinet.habit.edit', ['id' => $id]);
        }

        return $this->render('cabinet/habit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
