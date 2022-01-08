<?php declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Repository\ConfirmRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Table(name: 'user_confirm_requests')]
#[ORM\Entity(repositoryClass: ConfirmRequestRepository::class)]
class ConfirmRequest
{
    #[ORM\Id()]
    #[ORM\Column(name: 'token', type: 'string', length: 255,)]
    private string $token;

    #[ORM\Id()]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    public function __construct(string $token, User $user)
    {
        Assert::notEmpty($token);

        $this->token = $token;
        $this->user = $user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
