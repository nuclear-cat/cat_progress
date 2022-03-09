<?php declare(strict_types=1);

namespace App\Security\Cabinet;

use App\Security\UserIdentity;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function refreshUser(UserInterface $user): UserIdentity
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    public function loadUserByUsername(string $username)
    {
        die;

        dump($username); die;
    }

    public function __call(string $name, array $arguments)
    {
        die;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        die;
        // TODO: Implement loadUserByIdentifier() method.
    }
}
