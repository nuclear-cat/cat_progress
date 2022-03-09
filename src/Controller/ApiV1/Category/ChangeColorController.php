<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Category;

use App\Model\Progress\Repository\CategoryRepository;
use App\Model\Progress\Repository\UserRepository;
use App\Model\Progress\UseCase\Category\ChangeColor\Command;
use App\Model\Progress\UseCase\Category\ChangeColor\Handler;
use App\Model\Progress\ValueObject\Color;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

/**
 * @method UserIdentity getUser()
 */
class ChangeColorController extends AbstractController
{
    #[Route('/api/v1/category/{id}/change_color', name: 'api.v1.category.change_color', methods: ['POST'])]
    public function create(
        string             $id,
        Request            $request,
        Handler            $handler,
        UserRepository     $userRepository,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $user = $userRepository->get(Ulid::fromString($this->getUser()->getUserIdentifier()));
        $category = $categoryRepository->getByIdAndUser(Ulid::fromString($id), $user);

        $command = new Command(
            id: $category->getId(),
            color: Color::from($data['color']),
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
