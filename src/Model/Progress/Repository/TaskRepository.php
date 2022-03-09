<?php declare(strict_types=1);

namespace App\Model\Progress\Repository;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Project\Project;
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
        $task = $this->find($id);

        if ($task) {
            return $task;
        }

        throw new NotFoundException("Task {$id->toRfc4122()} not found.");
    }

    public function getByIdAndUser(Ulid $id, User $user): Task
    {
        $task = $this->findOneBy(['id' => $id, 'user' => $user]);

        if ($task) {
            return $task;
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
    public function findActiveUserTasks(User $user, ?Project $project = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->addSelect('p')
            ->andWhere('t.completedAt IS NULL')
            ->orderBy('t.id', 'DESC')
            ->join('t.user', 'u')
            ->leftJoin('t.project', 'p')
            ->leftJoin('p.memberships', 'm')
            ->leftJoin('m.member', 'me')
            ->andWhere('(u.id = :userId OR me.id = :memberId OR p.creator = :projectCreatorId)')
            ->setParameter('userId', $user->getId()->toRfc4122())
            ->setParameter('memberId', $user->getId()->toRfc4122())
            ->setParameter('projectCreatorId', $user->getId()->toRfc4122());

        if ($project) {
            $qb
                ->andWhere('p.id = :projectId')
                ->setParameter('projectId', $project->getId()->toRfc4122());
        }

        return $qb->getQuery()
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
