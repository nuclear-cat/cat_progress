<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Progress\UseCase\Category\Create;

class CreateController extends AbstractController
{
    #[Route('/cabinet/category/create', name: 'cabinet.category.create', methods: ['GET', 'POST'])]
    public function edit(
        Request         $request,
        Create\Handler  $handler,
    ): Response {

        $command = new Create\Command();

        $form = $this
            ->createForm(Create\Form::class, $command)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $handler->handle($command);

            return $this->redirectToRoute('cabinet.category.edit', ['id' => $id->toRfc4122()]);
        }


    }
}
