<?php declare(strict_types=1);

namespace App\Security\Cabinet;

use App\Model\User\Entity\Email;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Security\UserIdentity;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'cabinet.login';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository        $userRepository,
        private PasswordHasher        $hasher,
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') && $this->getLoginUrl($request) === $request->getPathInfo();
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('cabinet.home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
