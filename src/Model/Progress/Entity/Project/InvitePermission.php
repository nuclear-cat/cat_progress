<?php declare(strict_types=1);

namespace App\Model\Progress\Entity\Project;

use App\Model\Progress\Repository\Project\InvitePermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_projects_invite_permissions')]
#[ORM\Entity(repositoryClass: InvitePermissionRepository::class)]
#[ORM\UniqueConstraint(name: 'progress_projects_invite_permissions_invite_id_permission_key', columns: ['invite_id', 'permission'])]
class InvitePermission
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Invite::class, inversedBy: 'invitePermissions')]
    #[ORM\JoinColumn(name: 'invite_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Invite $invite;

    #[ORM\Column(name: 'permission', type: 'progress_project_permission', nullable: false)]
    private Permission $permission;

    public function __construct(
        Ulid $id,
        Invite $invite,
        Permission $permission,
    ) {
        $this->id = $id;
        $this->invite = $invite;
        $this->permission = $permission;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getInvite(): Invite
    {
        return $this->invite;
    }

    public function getPermission(): Permission
    {
        return $this->permission;
    }
}
