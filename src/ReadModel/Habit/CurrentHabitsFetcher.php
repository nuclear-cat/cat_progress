<?php declare(strict_types=1);

namespace App\ReadModel\Habit;

use Doctrine\DBAL\Connection;

class CurrentHabitsFetcher
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function fetch(
        \DateTimeImmutable $now,
        string             $userId,
    ): array {
        $weekday = $now->format('l');

        $stmt = $this->connection->prepare('
                SELECT 
                       h.id AS habit_id,
                       h.title AS habit_title,
                       h.title AS habit_description,
                       c.title AS category_title,
                       json_agg(hc.id) as completions
                FROM progress_habits h
                    JOIN progress_categories c ON h.category_id = c.id
                    JOIN progress_habit_weekdays hw on h.id = hw.habit_id
                    LEFT JOIN progress_habit_completions hc on h.id = hc.habit_id
                WHERE hw.weekday = :weekday
                GROUP BY h.id, c.id
        ');

        $stmt->bindParam('weekday', $weekday);

        return array_map(function (array $item) {
            return new CurrentHabitDto(
                habitId: $item['habit_id'],
                habitTitle: $item['habit_title'],
                habitDescription: $item['habit_description'],
                categoryTitle: $item['category_title'],
            );
        }, $stmt->executeQuery()->fetchAllAssociative());
    }
}
