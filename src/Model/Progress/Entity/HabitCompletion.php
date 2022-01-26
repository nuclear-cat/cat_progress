<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use App\Model\Progress\Repository\HabitCompletionRepository;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'progress_habit_completions')]
#[ORM\Entity(repositoryClass: HabitCompletionRepository::class)]
class HabitCompletion
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Habit::class, inversedBy: 'completions')]
    #[ORM\JoinColumn(name: 'habit_id', referencedColumnName: 'id', nullable: false)]
    private Habit $habit;

    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'completed_at', type: 'datetimetz_immutable')]
    private \DateTimeImmutable $completedAt;

    #[ORM\Column(type: 'smallint')]
    private int $completionPercentage = 0;

    #[ORM\Column(name: 'total_points', type: 'smallint')]
    private int $totalPoints = 1;

    #[ORM\Column(name: 'comment', type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(name: 'type', type: 'progress_habit_completion_type', nullable: false)]
    private HabitCompletionType $type;

    public function __construct(
        Ulid $id,
        Habit $habit,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $completedAt,
    ) {
        $this->id = $id;
        $this->habit = $habit;
        $this->createdAt = $createdAt;
        $this->completedAt = $completedAt;
        $this->type = HabitCompletionType::Success;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getHabit(): Habit
    {
        return $this->habit;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): self
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getCompletionPercentage(): int
    {
        return $this->completionPercentage;
    }

    public function setCompletionPercentage(int $completionPercentage): self
    {
        $this->completionPercentage = $completionPercentage;

        return $this;
    }

    public function getCompletedAt(): \DateTimeImmutable
    {
        return $this->completedAt;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getType(): HabitCompletionType
    {
        return $this->type;
    }

    public function setType(HabitCompletionType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
