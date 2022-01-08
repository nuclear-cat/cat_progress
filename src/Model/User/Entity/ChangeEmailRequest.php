<?php declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Repository\ChangeEmailRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

#[ORM\Table(name: 'user_change_email_requests')]
#[ORM\Entity(repositoryClass: ChangeEmailRequestRepository::class)]
class ChangeEmailRequest
{
    const TOKEN_LIFE_HOURS = 4;

    use CreatedAtTrait;

    #[ORM\Id()]
    #[ORM\Column(type: "ulid")]
    private Ulid $id;

    #[ORM\Column(name: 'token', type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(name: 'email', type: 'string', length: 255)]
    private string $email;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'emailChangeRequests')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(name: 'is_confirmed', type: 'boolean',)]
    private bool $isConfirmed;

    public function __construct(Ulid $id, string $token, User $user, \DateTimeImmutable $createdAt)
    {
        Assert::notEmpty($token);

        $this->id          = $id;
        $this->token       = $token;
        $this->createdAt   = $createdAt;
        $this->isConfirmed = false;
        $this->user        = $user;

    }

    public static function create(Ulid $id, string $token, User $user, \DateTimeImmutable $createdAt): self
    {
        return new self($id, $token, $user, $createdAt);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isExpired(\DateTimeImmutable $now): bool
    {
        return $this->createdAt->modify('+' . self::TOKEN_LIFE_HOURS . ' hours') <= $now;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
