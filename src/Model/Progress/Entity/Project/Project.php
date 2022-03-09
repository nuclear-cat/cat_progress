<?php declare(strict_types=1);

namespace App\Model\Progress\Entity\Project;

use App\Model\Progress\Entity\Task;
use App\Model\Progress\Entity\User;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\ValueObject\Color;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_projects')]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false)]
    private User $creator;

    #[ORM\Column(name: 'color', type: 'progress_color', length: 25)]
    private Color $color = Color::Blue;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Membership::class)]
    private Collection $memberships;

    #[Pure] public function __construct(
        Ulid   $id,
        string $title,
        User   $creator
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->creator = $creator;
        $this->tasks = new ArrayCollection();
        $this->memberships = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setColor(Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks->toArray();
    }

    /**
     * @return Membership[]
     */
    public function getMemberships(): array
    {
        return $this->memberships->toArray();
    }

    /**
     * @return User[]
     */
    public function getMembers(): array
    {
        return array_map(function(Membership $membership): User {
            return $membership->getMember();
        }, $this->memberships->toArray());
    }
}
