<?php declare(strict_types=1);

namespace App\Model\Progress\Repository;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\User;
use App\Model\Progress\Entity\Weekday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method Habit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habit[]    findAll()
 * @method Habit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habit::class);
    }

    public function get(Ulid $id): Habit
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Habit {$id->toRfc4122()} not found.");
    }

    public function getByIdAndUser(Ulid $id, User $user): Habit
    {
        $result = $this->findOneBy(['id' => $id, 'user' => $user]);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Habit {$id->toRfc4122()} not found.");
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Habit $habit): void
    {
        $this->_em->persist($habit);
    }

    /**
     * @return Habit[]
     */
    public function getForCalendar(
        User $user,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        ?Weekday $weekday = null,
    ): array {
        $qb = $this->createQueryBuilder('h')
            ->addSelect('c')
            ->addSelect('cat')
            ->leftJoin('h.completions', 'c', Expr\Join::WITH, '(c.completedAt >= :from AND c.completedAt <= :to)')
            ->leftJoin('h.category', 'cat')
            ->join('cat.user', 'u')
            ->join('h.habitWeekdays', 'hw')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId()->toRfc4122())
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        if ($weekday) {
            $qb
                ->andWhere('hw.weekday = :weekday')
                ->setParameter('weekday', $weekday->value);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Habit[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('h')
            ->orderBy('h.id', 'DESC')
            ->join('h.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId()->toRfc4122())
            ->getQuery()
            ->getResult();
    }

    public function remove(Habit $habit): void
    {
        $this->_em->remove($habit);
    }
}
