<?php declare(strict_types=1);

namespace App\Security\Api;

use App\Model\User\Entity\Email;
use App\Model\User\Exception\AuthenticationFailException;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\AccessTokenManager;
use App\Model\User\Service\PasswordHasher;
use App\Security\UserIdentity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class BasicAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UserRepository     $userRepository,
        private PasswordHasher     $passwordHasher,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->server->has('PHP_AUTH_USER')
            && $request->server->has('PHP_AUTH_PW')
            && preg_match('/^Basic/', $request->headers->get('authorization'));
    }

    public function authenticate(Request $request): Passport
    {
        $user = $this->userRepository->getByEmail(new Email($request->server->get('PHP_AUTH_USER')));

        if (!$this->passwordHasher->verify($request->server->get('PHP_AUTH_PW'), $user->getPasswordHash())) {
            throw new BadCredentialsException();
        }

        return new Passport(
            new UserBadge($user->getId()->toRfc4122(), function () use ($user): ?UserIdentity {
                return new UserIdentity(
                    $user->getEmail()->getValue(),
                    $user->getId()->toRfc4122(),
                    $user->getPasswordHash(),
                    $user->getTimezone(),
                    $user->getName(),
                );
            }),
            new CustomCredentials(function (): bool {
                return true;
            }, $user->getId()->toRfc4122())
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        if ($exception instanceof AuthenticationFailException) {
            return new JsonResponse([
                'success' => false,
                'error' => $exception->getReason(),
                'message' => $exception->getMessage(),
            ], 401);
        }

        return new JsonResponse([
            'success' => false,
            'error' => 'unknown',
            'message' => $exception->getMessage(),
        ], 401);
    }
}
