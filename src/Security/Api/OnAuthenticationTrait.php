<?php declare(strict_types=1);

namespace App\Security\Api;

use App\Model\User\Exception\AuthenticationFailException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

trait OnAuthenticationTrait
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $deviceInfo = $data['device_info'] ?? throw new AuthenticationException('No device info');

        $userId = $token->getUser()->getUserIdentifier();

        return new JsonResponse([
            'token_type' => 'Bearer',
            'access_token' => $this->accessTokenManager->createToken($userId, $token->getUser()->getRoles()),
            'refresh_token' => $this->refreshTokenManager->createToken($userId, $deviceInfo),
        ], Response::HTTP_OK);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        if ($exception instanceof AuthenticationFailException) {
            return new JsonResponse([
                'error' => $exception->getReason(),
                'message' => $exception->getMessage(),
            ], 401);
        }

        return new JsonResponse([
            'error' => 'unknown',
            'message' => $exception->getMessage(),
        ], 401);
    }
}
