<?php declare(strict_types=1);

namespace App\Model\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\User\Entity\RefreshSession;

/**
 * @method RefreshSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshSession[]    findAll()
 * @method RefreshSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshSession::class);
    }

    public function add(RefreshSession $session): void
    {
        $this->_em->persist($session);
    }

    public function findByUserIdAndDeviceInfo(string $deviceInfo, string $userId): array
    {
        return $this
            ->createQueryBuilder('s')
            ->join('s.user', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('s.deviceInfo = :deviceInfo')
            ->setParameter('userId', $userId)
            ->setParameter('deviceInfo', $deviceInfo)
            ->getQuery()
            ->getResult();
    }
}
