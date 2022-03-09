<?php declare(strict_types=1);

namespace App\Model\Progress\Repository\Project;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Project\InvitePermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method InvitePermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvitePermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvitePermission[]    findAll()
 * @method InvitePermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitePermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvitePermission::class);
    }

    public function get(Ulid $id): InvitePermission
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Invite permission {$id->toRfc4122()} not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(InvitePermission $invitePermission): void
    {
        $this->_em->persist($invitePermission);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(InvitePermission $invitePermission): void
    {
        $this->_em->remove($invitePermission);
    }
}
