<?php declare(strict_types=1);

namespace App\Model\Progress\Repository\Project;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Project\MembershipPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method MembershipPermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembershipPermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembershipPermission[]    findAll()
 * @method MembershipPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipPermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembershipPermission::class);
    }

    public function get(Ulid $id): MembershipPermission
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Membership permission {$id->toRfc4122()} not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(MembershipPermission $membershipPermission): void
    {
        $this->_em->persist($membershipPermission);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(MembershipPermission $membershipPermission): void
    {
        $this->_em->remove($membershipPermission);
    }
}
