<?php declare(strict_types=1);

namespace App\Model\Progress\Entity\Project;

use App\Model\Progress\Repository\Project\MembershipPermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_projects_membership_permissions')]
#[ORM\Entity(repositoryClass: MembershipPermissionRepository::class)]
#[ORM\UniqueConstraint(name: 'progress_projects_membership_permissions_invite_id_permission_key', columns: ['membership_id', 'permission'])]
class MembershipPermission
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Membership::class, inversedBy: 'membershipPermissions')]
    #[ORM\JoinColumn(name: 'membership_id', referencedColumnName: 'id', nullable: false)]
    private Membership $membership;

    #[ORM\Column(name: 'permission', type: 'progress_project_permission', nullable: false)]
    private Permission $permission;

    public function __construct(
        Ulid $id,
        Membership $membership,
        Permission $permission,
    ) {
        $this->id = $id;
        $this->membership = $membership;
        $this->permission = $permission;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getMembership(): Membership
    {
        return $this->membership;
    }

    public function getPermission(): Permission
    {
        return $this->permission;
    }
}
