<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'progress_habit_weekdays')]
#[ORM\Entity()]
#[ORM\UniqueConstraint(name: 'progress_habit_weekdays_weekday_habit_id', columns: ['weekday', 'habit_id'])]
class HabitWeekday
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Habit::class, inversedBy: 'habitWeekdays')]
    #[ORM\JoinColumn(name: 'habit_id', referencedColumnName: 'id', nullable: false)]
    private Habit $habit;

    #[ORM\Column(name: 'weekday', type: 'progress_weekday', nullable: false)]
    private Weekday $weekday;

    public function __construct(
        Ulid    $id,
        Habit   $habit,
        Weekday $weekday
    ) {
        $this->id = $id;
        $this->habit = $habit;
        $this->weekday = $weekday;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getHabit(): Habit
    {
        return $this->habit;
    }

    public function getWeekday(): Weekday
    {
        return $this->weekday;
    }
}
