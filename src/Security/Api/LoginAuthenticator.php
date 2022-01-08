<?php declare(strict_types=1);

namespace App\Security\Api;

use App\Model\User\Entity\Email;
use App\Model\User\Exception\AuthenticationFailException;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\AccessTokenManager;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\RefreshTokenManager;
use App\Security\UserIdentity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class LoginAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UserRepository      $userRepository,
        private PasswordHasher      $hasher,
        private AccessTokenManager  $accessTokenManager,
        private RefreshTokenManager $refreshTokenManager,
    ) {
    }

    public function supports(Request $request): bool
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'])) {
            return false;
        }

        if (!isset($data['password'])) {
            return false;
        }

        if (!isset($data['device_info'])) {
            return false;
        }

        return true;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? throw new AuthenticationFailException('Invalid credentials.', 'No email');
        $password = $data['password'] ?? throw new AuthenticationFailException('Invalid credentials.', 'No password');
        $deviceInfo = $data['device_info'] ?? throw new AuthenticationFailException('Invalid credentials.', 'No device info');

        return new Passport(
            new UserBadge(
                $email,
                function (string $email): UserIdentity {
                    $user = $this->userRepository->findByEmail(new Email($email));

                    if (!$user) {
                        throw new CustomUserMessageAuthenticationException('Invalid credentials.');
                    }

                    if (!$user->isActive()) {
                        throw new CustomUserMessageAuthenticationException('User is not active.');
                    }

                    return new UserIdentity(
                        $user->getEmail()->getValue(),
                        $user->getId()->toRfc4122(),
                        $user->getPasswordHash(),
                        $user->getTimezone(),
                        $user->getName(),
                    );
                },
            ),
            new CustomCredentials(
                function ($credentials, UserInterface $user) {
                    return $this->hasher->verify($credentials, $user->getPassword());
                },
                $password,
            ),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): JsonResponse
    {
        $userId = $token->getUser()->getUserIdentifier();
        $data = json_decode($request->getContent(), true);
        $deviceInfo = $data['device_info'] ?? throw new AuthenticationFailException('Invalid credentials.', 'No device info');

        $accessToken = $this->accessTokenManager->createToken($userId, ['api']);
        $refreshToken = $this->refreshTokenManager->createToken($userId, $deviceInfo);

        return new JsonResponse([
            'success' => true,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $exception->getMessage(),
        ], 401);
    }
}
