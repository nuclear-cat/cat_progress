<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use App\Model\Progress\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_categories')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'title', type: 'string', length: 120)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Habit::class)]
    private Collection $habits;

    #[ORM\Column(name: 'color', type: 'progress_category_color', length: 25)]
    private CategoryColor $color = CategoryColor::Blue;

    #[Pure] public function __construct(
        Ulid $id,
        string $title,
        User $user,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->user = $user;
        $this->habits = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): User
    {
        return $this->user;
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

    public function getHabits(): array
    {
        return $this->habits->toArray();
    }

    public function getColor(): CategoryColor
    {
        return $this->color;
    }

    public function setColor(CategoryColor $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function setTitle(string $title): self
    {
        return $this;
    }
}
