<?php declare(strict_types=1);

namespace App\Model\Progress\Entity\Project;

use App\Model\Progress\Repository\Project\InviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_project_invites')]
#[ORM\Entity(repositoryClass: InviteRepository::class)]
class Invite
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: false)]
    private Project $project;

    #[ORM\Column(name: 'token', type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToMany(mappedBy: 'invite', targetEntity: InvitePermission::class)]
    private Collection $invitePermissions;

    #[Pure] public function __construct(
        Ulid               $id,
        Project            $project,
        string             $token,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->project = $project;
        $this->token = $token;
        $this->createdAt = $createdAt;
        $this->invitePermissions = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return InvitePermission[]
     */
    public function getInvitePermissions(): array
    {
        return $this->invitePermissions->toArray();
    }

    public function addInvitePermission(InvitePermission $permission): self
    {
        $this->invitePermissions[] = $permission;

        return $this;
    }
}
