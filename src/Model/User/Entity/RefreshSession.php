<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use App\Model\User\Repository\RefreshSessionRepository;

#[ORM\Table(name: 'user_refresh_sessions')]
#[ORM\Entity(repositoryClass: RefreshSessionRepository::class)]
class RefreshSession
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'expires_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $expiresAt;

    #[ORM\Column(name: 'device_info', type: 'string', length: 128, nullable: false)]
    private string $deviceInfo;

    #[ORM\Column(name: 'is_revoked', type: 'boolean')]
    private bool $isRevoked = false;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    public function __construct(
        Ulid               $id,
        \DateTimeImmutable $expiresAt,
        string             $deviceInfo,
        User               $user
    ) {
        $this->id = $id;
        $this->expiresAt = $expiresAt;
        $this->deviceInfo = $deviceInfo;
        $this->user = $user;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getDeviceInfo(): string
    {
        return $this->deviceInfo;
    }

    public function getIsRevoked(): bool
    {
        return $this->isRevoked;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setIsRevoked(bool $isRevoked): self
    {
        $this->isRevoked = $isRevoked;

        return $this;
    }
}
