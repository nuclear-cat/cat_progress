<?php declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\ConfirmRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

class ConfirmRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfirmRequest::class);
    }

    public function findByUserIdAndToken(Ulid $userId, string $token): ?ConfirmRequest
    {
        $result = $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('t.token = :token')
            ->setParameter('token', $token)
            ->setParameter('userId', $userId->toRfc4122())
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(ConfirmRequest $user): void
    {
        $this->_em->persist($user);
    }
}
