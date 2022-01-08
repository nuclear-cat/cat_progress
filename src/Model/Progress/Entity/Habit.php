<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use App\Model\Progress\Repository\HabitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_habits')]
#[ORM\Entity(repositoryClass: HabitRepository::class)]
class Habit
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'title', type: 'string', length: 120)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'habits')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category $category;

    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'started_at', type: 'datetimetz_immutable')]
    private \DateTimeImmutable $startedAt;

    /**
     * @var ArrayCollection|Collection|HabitWeekday[]
     */
    #[ORM\OneToMany(mappedBy: 'habit', targetEntity: HabitWeekday::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection|ArrayCollection|array $habitWeekdays;

    /**
     * @var ArrayCollection|Collection|HabitCompletion[]
     */
    #[ORM\OneToMany(mappedBy: 'habit', targetEntity: HabitCompletion::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection|ArrayCollection|array $completions;

    #[ORM\Column(name: 'total_points', type: 'smallint')]
    private int $totalPoints = 1;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    readonly private User $user;

    #[Pure] public function __construct(
        Ulid               $id,
        string             $title,
        Category           $category,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $startedAt,
        User               $user,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->createdAt = $createdAt;
        $this->startedAt = $startedAt;
        $this->habitWeekdays = new ArrayCollection();
        $this->completions = new ArrayCollection();
        $this->user = $user;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): Category
    {
        return $this->category;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    /**
     * @return HabitWeekday[]
     */
    public function getHabitWeekdays(): array
    {
        return $this->habitWeekdays->toArray();
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function addHabitWeekday(HabitWeekday $habitWeekday): void
    {
        $this->habitWeekdays->add($habitWeekday);
    }

    /**
     * @return Weekday[]
     */
    #[Pure] public function getWeekdays(): array
    {
        $weekdays = [];

        foreach ($this->habitWeekdays as $habitWeekday) {
            $weekdays[] = $habitWeekday->getWeekday();
        }

        return $weekdays;
    }

    public function isActual(\DateTimeImmutable $date, \DateTimeZone $timezone): bool
    {
        foreach ($this->getWeekdays() as $weekday) {
            if ($weekday->value !== $date->format('l')) {
                continue;
            }

            if ($this->startedAt > $date) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function getCompletions(): array
    {
        return $this->completions->toArray();
    }

    public function getDayCompletions(\DateTimeImmutable $day): array
    {
        $dayStart = $day->setTime(0, 0, 0);
        $dayEnd = $day->setTime(23, 59, 59);

        $completions = [];

        foreach ($this->completions as $completion) {
            if ($completion->getCompletedAt() >= $dayStart && $completion->getCompletedAt() <= $dayEnd) {
                $completions[] = $completion;
            }
        }

        return $completions;
    }

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function setTotalPoints(int $totalPoints): self
    {
        $this->totalPoints = $totalPoints;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
