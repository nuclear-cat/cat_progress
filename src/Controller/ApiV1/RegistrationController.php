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
        $command->target = $data['target'] ?? null;

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/api/v1/register/confirm', name: 'api.v1.register.confirm', methods: ['POST'])]
    public function confirm(Request $request, Register\Confirm\Handler $handler): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Register\Confirm\Command(
            Ulid::fromString($data['id']),
            $data['token'],
        );

        $handler->handle($command);

        return $this->json([
            'success' => true,
        ]);
    }
}
