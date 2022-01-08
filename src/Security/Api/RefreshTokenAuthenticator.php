<?php declare(strict_types=1);

namespace App\Security\Api;

use App\Model\User\Exception\AuthenticationFailException;
use App\Model\User\Exception\JWTDecodeFailureException;
use App\Model\User\Repository\RefreshSessionRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\AccessTokenManager;
use App\Model\User\Service\RefreshTokenManager;
use App\Security\UserIdentity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class RefreshTokenAuthenticator extends AbstractAuthenticator
{
    use OnAuthenticationTrait;

    public function __construct(
        private AccessTokenManager $accessTokenManager,
        private RefreshTokenManager $refreshTokenManager,
        private UserRepository $userRepository,
        private RefreshSessionRepository $refreshSessionRepository,
    ) {
    }

    public function supports(Request $request): bool
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return false;
        }

        if (isset($data['refresh_token'])) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     * @throws \JsonException
     */
    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $refreshToken = $data['refresh_token'] ?? throw new AuthenticationException('No refresh token');

        try {
            $claims = $this->refreshTokenManager->decode($refreshToken);
        } catch (JWTDecodeFailureException $exception) {
            throw new AuthenticationFailException($exception->getMessage(), $exception->getReason());
        }

        $refreshSession = $this->refreshSessionRepository->find($claims['refresh_session_id']);

        if (!$refreshSession) {
            throw new AuthenticationException('No refresh session.');
        }

        $user = $refreshSession->getUser();

        return new Passport(
            new UserBadge($user->getId()->toRfc4122(), function () use ($user): UserIdentity  {
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
}
