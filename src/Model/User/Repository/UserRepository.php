<?php declare(strict_types=1);

namespace App\Model\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
use Symfony\Component\Uid\Ulid;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmail(Email $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param Ulid[] $ids
     * @return User[]
     */
    public function findByIds(array $ids): array
    {
        return $this->findBy(['id' => array_map(function ($id) {
            return $id->toRfc4122();
        }, $ids)]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->andWhere('u.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    public function getByEmail(Email $email): User
    {
        $result = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();

        if (empty($result)) {
            throw new \DomainException("User with email {$email->getValue()} doesn't exists");
        }

        return $result[0];
    }

    public function get(Ulid $id): User
    {
        $user = $this->find($id);

        if (!$user) {
            throw new \DomainException("User {$id} doesn't exists");
        }

        return $user;
    }

    public function add(User $user): void
    {
        $this->_em->persist($user);
    }
}
