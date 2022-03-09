<?php declare(strict_types=1);

namespace App\Model\Progress\Entity\Project;

use App\Model\Progress\Entity\User;
use App\Model\Progress\Repository\Project\MembershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_project_memberships')]
#[ORM\Entity(repositoryClass: MembershipRepository::class)]
#[ORM\UniqueConstraint(name: 'progress_project_memberships_member_id_project_id_key', columns: ['member_id', 'project_id'])]
class Membership
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'memberships')]
    #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'id', nullable: false)]
    private User $member;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'memberships')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: false)]
    private Project $project;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToMany(mappedBy: 'membership', targetEntity: MembershipPermission::class)]
    private Collection $membershipPermissions;

    #[Pure] public function __construct(
        Ulid               $id,
        User               $member,
        Project            $project,
        \DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->member = $member;
        $this->project = $project;
        $this->createdAt = $createdAt;
        $this->membershipPermissions = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getMember(): User
    {
        return $this->member;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return MembershipPermission[]
     */
    public function getMembershipPermissions(): array
    {
        return $this->membershipPermissions->toArray();
    }

    /**
     * @return Permission[]
     */
    public function getPermissions(): array
    {
        return array_map(function(MembershipPermission $membershipPermission): Permission {
            return $membershipPermission->getPermission();
        }, $this->membershipPermissions->toArray());
    }
}
