<?php declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity()]
#[ORM\Table(name: 'user_password_requests')]
#[ORM\Index(name: 'user_password_requests_token_idx', columns: ['token'])]
class ResetPasswordRequest
{
    const TOKEN_LIFE_HOURS = 4;

    use CreatedAtTrait;

    #[ORM\Id()]
    #[ORM\Column(name: 'token', type: 'string', length: 255)]
    private string $token;

    #[ORM\Id()]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'passwordResetRequests')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(name: 'is_confirmed', type: 'boolean',)]
    private bool $isConfirmed;

    public function __construct(string $token, \DateTimeImmutable $createdAt, User $user)
    {
        Assert::notEmpty($token);

        $this->token     = $token;
        $this->createdAt = $createdAt;
        $this->user      = $user;
    }


    public static function createNew(string $token, \DateTimeImmutable $expiresAt, User $user): self
    {
        $resetToken              = new self($token, $expiresAt, $user);
        $resetToken->isConfirmed = false;

        return $resetToken;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isExpired(\DateTimeImmutable $now): bool
    {
        return $this->createdAt->modify('+' . self::TOKEN_LIFE_HOURS . ' hours') <= $now;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setExpiredAt(\DateTimeImmutable $expiredAt): self
    {
        $this->expiresAt = $expiredAt;

        return $this;
    }
}
