<?php declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\ChangeEmailRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChangeEmailRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChangeEmailRequest::class);
    }

    public function findByUserIdAndToken(string $userId, string $token): ?ChangeEmailRequest
    {
        $result = $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('r.token = :token')
            ->setParameter('token', $token)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function add(ChangeEmailRequest $request): void
    {
        $this->getEntityManager()->persist($request);
    }
}
