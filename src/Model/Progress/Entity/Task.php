<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use App\Model\Progress\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_tasks')]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'title', type: 'string', length: 120)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'completed_at', type: 'datetimetz_immutable', nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(type: 'smallint')]
    private int $completionPercentage = 0;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: true)]
    private ?Category $category;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    public function __construct(
        Ulid $id,
        string $title,
        \DateTimeImmutable $createdAt,
        User $user,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->user = $user;
    }

    public function setCompletionPercentage(int $completionPercentage): self
    {
        if ($completionPercentage > 100 || $completionPercentage < 0) {
            throw new \InvalidArgumentException("Invalid percentage value: $completionPercentage.");
        }

        $this->completionPercentage = $completionPercentage;

        return $this;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCompletionPercentage(): int
    {
        return $this->completionPercentage;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): self
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
