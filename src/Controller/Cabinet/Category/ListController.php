<?php declare(strict_types=1);

namespace App\Controller\Cabinet\Category;

use App\Model\Progress\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('/cabinet/category/list', name: 'cabinet.category.list', methods: ['GET'])]
    public function edit(
        CategoryRepository $categoryRepository,
    ): Response {
        $categories = $categoryRepository->findAll();

        return $this->render('cabinet/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }
}