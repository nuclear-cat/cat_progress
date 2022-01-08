<?php declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface
{
    private string $email;
    private string $id;
    private string $passwordHash;
    private \DateTimeZone $timezone;
    private string $name;

    public function __construct(
        string $email,
        string $id,
        string $passwordHash,
        \DateTimeZone $timezone,
        string $name,
    ) {
        $this->email = $email;
        $this->id = $id;
        $this->passwordHash = $passwordHash;
        $this->timezone = $timezone;
        $this->name = $name;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getTimezone(): \DateTimeZone
    {
        return $this->timezone;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
