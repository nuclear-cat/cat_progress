<?php declare(strict_types=1);

namespace App\Model\Progress\Repository;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Category;
use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function get(Ulid $id): Category
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Category {$id->toRfc4122()} not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Category $habit): void
    {
        $this->_em->persist($habit);
    }

    /**
     * @return Habit[]
     */
    public function findByUser(User $user): array
    {
        return $this
            ->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId()->toRfc4122())
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Category $category): void
    {
        $this->_em->remove($category);
    }
}
