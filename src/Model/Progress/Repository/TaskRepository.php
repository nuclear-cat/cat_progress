<?php declare(strict_types=1);

namespace App\Model\Progress\Repository;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Task;
use App\Model\Progress\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function get(Ulid $id): Task
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Task {$id->toRfc4122()} not found.");
    }

    public function add(Task $task): void
    {
        $this->_em->persist($task);
    }

    public function remove(Task $task): void
    {
        $this->_em->remove($task);
    }

    /**
     * @return Task[]
     */
    public function findActiveUserTasks(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.completedAt IS NULL')
            ->orderBy('t.id', 'DESC')
            ->join('t.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId()->toRfc4122())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Task[]
     */
    public function findCompletedUserTasks(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.completedAt IS NOT NULL')
            ->join('t.user', 'u')
            ->orderBy('t.completedAt', 'DESC')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId()->toRfc4122())
            ->getQuery()
            ->getResult();
    }

}
