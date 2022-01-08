<?php declare(strict_types=1);

namespace App\ReadModel\Habit;

final class CurrentHabitDto
{
    public function __construct(
        public readonly string $habitId,
        public readonly string $habitTitle,
        public readonly string $habitDescription,
        public readonly string $categoryTitle,
    ) {}
}
