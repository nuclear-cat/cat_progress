<?php declare(strict_types=1);

namespace App\Model\Progress\Repository;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\Project;
use App\Model\Progress\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function get(Ulid $id): Project
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Category {$id->toRfc4122()} not found.");
    }

    public function getByIdAndUser(Ulid $id, User $user): Project
    {
        $category = $this->findOneBy(['id' => $id, 'user' => $user]);

        if ($category) {
            return $category;
        }

        throw new NotFoundException("Category {$id->toRfc4122()} not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Project $habit): void
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
    public function remove(Project $category): void
    {
        $this->_em->remove($category);
    }
}
