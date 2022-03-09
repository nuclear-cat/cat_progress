<?php declare(strict_types=1);

namespace App\Model\Progress\Repository\Project;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\Project\Membership;
use App\Model\Progress\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method Membership|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membership|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membership[]    findAll()
 * @method Membership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membership::class);
    }

    public function get(Ulid $id): Membership
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Project membership {$id->toRfc4122()} not found.");
    }

    public function getByIdAndCreator(Ulid $id, User $user): Membership
    {
        $membership = $this->findOneBy(['id' => $id, 'creator' => $user]);

        if ($membership) {
            return $membership;
        }

        throw new NotFoundException("Project membership {$id->toRfc4122()} not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Membership $habit): void
    {
        $this->_em->persist($habit);
    }

    /**
     * @return Habit[]
     */
    public function findByUser(User $creator): array
    {
        return $this
            ->createQueryBuilder('c')
            ->join('c.creator', 'cr')
            ->andWhere('cr.id = :creatorId')
            ->setParameter('creatorId', $creator->getId()->toRfc4122())
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Membership $membership): void
    {
        $this->_em->remove($membership);
    }
}
