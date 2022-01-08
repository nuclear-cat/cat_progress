<?php declare(strict_types=1);

namespace App\Model\Progress\Repository;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\HabitCompletion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @method HabitCompletion|null find($id, $lockMode = null, $lockVersion = null)
 * @method HabitCompletion|null findOneBy(array $criteria, array $orderBy = null)
 * @method HabitCompletion[]    findAll()
 * @method HabitCompletion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitCompletionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabitCompletion::class);
    }

    public function get(Ulid $id): HabitCompletion
    {
        $result = $this->find($id);

        if ($result) {
            return $result;
        }

        throw new NotFoundException("Habit completion {$id->toRfc4122()} not found.");
    }

    public function add(HabitCompletion $habit): void
    {
        $this->_em->persist($habit);
    }
}
