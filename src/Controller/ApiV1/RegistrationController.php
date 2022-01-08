<?php declare(strict_types=1);

namespace App\Controller\ApiV1;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\Register;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

class RegistrationController extends AbstractController
{
    #[Route('/api/v1/register', name: 'api.v1.register', methods: ['POST'])]
    public function request(Request $request, Register\Request\Handler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Register\Request\Command();
        $command->name = $data['name'];
        $command->email = $data['email'];
        $command->password = $data['password'];
        $command->timezone = $data['timezone'];

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/cabinet/register/{id}/{token}/confirm', name: 'api.v1.register.confirm', methods: ['POST'])]
    public function confirm(string $id, string $token, Register\Confirm\Handler $handler): Response
    {
        $command = new Register\Confirm\Command(Ulid::fromString($id), $token);
        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
