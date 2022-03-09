<?php declare(strict_types=1);

namespace App\Model\Progress\Repository\Project;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Project\Invite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method Invite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invite[]    findAll()
 * @method Invite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invite::class);
    }

    public function get(Ulid $id): Invite
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Invite {$id->toRfc4122()} not found.");
    }

    public function getByIdAndToken(Ulid $id, string $token): Invite
    {
        $result = $this->findOneBy(['id' => $id, 'token' => $token]);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Invite {$id->toRfc4122()} with token $token not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Invite $invite): void
    {
        $this->_em->persist($invite);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Invite $invite): void
    {
        $this->_em->remove($invite);
    }
}
