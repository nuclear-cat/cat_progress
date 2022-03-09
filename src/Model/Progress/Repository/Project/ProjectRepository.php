<?php declare(strict_types=1);

namespace App\Model\Progress\Repository\Project;

use App\Exception\NotFoundException;
use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\Project\Project;
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

        throw new NotFoundException("Project {$id->toRfc4122()} not found.");
    }

    public function getByIdAndCreator(Ulid $id, User $user): Project
    {
        $project = $this->findOneBy(['id' => $id, 'creator' => $user]);

        if ($project) {
            return $project;
        }

        throw new NotFoundException("Project {$id->toRfc4122()} not found.");
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
    public function findByCreatorOrMember(User $creator): array
    {
        return $this
            ->createQueryBuilder('c')
            ->join('c.creator', 'cr')
            ->andWhere('(cr.id = :creatorId OR me.id = :memberId)')
            ->leftJoin('c.memberships', 'm')
            ->leftJoin('m.member', 'me')
            ->setParameter('creatorId', $creator->getId()->toRfc4122())
            ->setParameter('memberId', $creator->getId()->toRfc4122())
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Project $project): void
    {
        $this->_em->remove($project);
    }
}
