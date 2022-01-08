<?php declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'email', type: 'user_email', length: 120, unique: true, nullable: true)]
    private ?Email $email;

    #[ORM\Column(name: 'password_hash', type: 'string', length: 255, nullable: true)]
    private ?string $passwordHash;

    #[ORM\Column(name: 'name', type: 'string', length: 120, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'timezone', type: 'user_timezone', length: 64, nullable: false)]
    private \DateTimeZone $timezone;

    #[ORM\Column(name: 'status', type: 'user_status')]
    private Status $status;

    public function __construct(
        Ulid          $id,
        ?Email        $email,
        ?string       $passwordHash,
        string        $name,
        \DateTimeZone $timezone,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->name = $name;
        $this->timezone = $timezone;
        $this->status = Status::Wait;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimezone(): \DateTimeZone
    {
        return $this->timezone;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->getStatus() === Status::Active;
    }

    public function activate(): self
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }

        $this->status = Status::Active;

        return $this;
    }

    #[Pure] public static function signUpByEmail(
        Ulid          $id,
        Email         $email,
        string        $name,
        string        $passwordHash,
        \DateTimeZone $timezone,
    ): self {
        return new self(
            $id,
            $email,
            $passwordHash,
            $name,
            $timezone,
        );
    }

    public function setPasswordHash(?string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }
}
