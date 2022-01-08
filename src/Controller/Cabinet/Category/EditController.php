<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Category;

use App\Model\Progress\Entity\CategoryColor;
use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\UseCase\Category\Update;
use App\Model\Progress\UseCase\Category\Update\Command;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class EditController extends AbstractController
{
    #[Route('/cabinet/category/{id}/edit', name: 'cabinet.category.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request         $request,
        string          $id,
        CategoryRepository $categoryRepository,
        Update\Handler  $handler,
    ): Response {
        $category = $categoryRepository->get(Ulid::fromString($id));

        $command = new Command(
            id: $category->getId(),
            title: $category->getTitle(),
            description: $category->getDescription(),
            color: $category->getColor(),
        );

        $form = $this
            ->createForm(Update\Form::class, $command)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('cabinet.category.edit', ['id' => $id]);
        }

        return $this->render('cabinet/category/edit.html.twig', [
            'category' => $category,
            'colors' => CategoryColor::cases(),
            'form' => $form->createView(),
        ]);
    }
}
