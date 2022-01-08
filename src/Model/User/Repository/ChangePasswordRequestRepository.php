<?php declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\ResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChangePasswordRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    public function findByToken(string $token): ?ResetPasswordRequest
    {
        $result = $this->createQueryBuilder('t')
            ->andWhere('t.token = :token')
            ->setParameter(':token', $token)
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function add(ResetPasswordRequest $token)
    {
        $this->getEntityManager()->persist($token);
    }
}
