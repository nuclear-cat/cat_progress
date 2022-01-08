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
}
