<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use App\Model\Progress\Entity\Project\Membership;
use App\Model\Progress\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 120)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Membership::class)]
    private Collection $memberships;

    #[Pure] public function __construct(Ulid $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->memberships = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Membership[]
     */
    public function getMemberships(): array
    {
        return $this->memberships->toArray();
    }
}
