<?php declare(strict_types=1);

namespace App\DataFixtures\Progress;

use App\Model\Progress\Entity\Category;
use App\Model\Progress\Entity\Habit;
use App\Model\Progress\Entity\HabitWeekday;
use App\Model\Progress\Entity\User;
use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\ValueObject\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class HabitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $mainUser */
        $mainUser = $this->getReference(UserFixtures::PROGRESS_MAIN_USER_REF);

        /** @var \App\Model\User\Entity\User $mainUser */
        $mainAuthUser = $this->getReference(UserFixtures::AUTH_MAIN_USER_REF);

        $items = [
            [
                'title' => 'Sport',
                'color' => Color::Red,
                'habits' => [
                    ['title' => 'Do abs workout - 3 sets', 'weekdays' => [ Weekday::Monday ]],
                    ['title' => 'Go to pilates', 'weekdays' => [ Weekday::Tuesday, Weekday::Thursday ] ],
                    ['title' => 'Push ups - 3 sets', 'weekdays' => [], ],
                ],
            ], [
                'title' => 'English',
                'color' => Color::Blue,
                'habits' => [
                    ['title' => 'Memorize the words in Puzzle English - 10 minutes', 'weekdays' => [ Weekday::Friday, ], ],
                    ['title' => 'Читай книгу по английскому 10 минут', 'weekdays' => [], ],
                ],
            ],
        ];

        foreach ($items as $categoryItem) {
            $category = (new Category(
                new Ulid(),
                $categoryItem['title'],
                $mainUser,
            ))->setColor($categoryItem['color']);

            $manager->persist($category);

            foreach ($categoryItem['habits'] as $habitItem) {
                $habit = new Habit(
                    new Ulid(),
                    $habitItem['title'],
                    $category,
                    (new \DateTimeImmutable())
                        ->setTimezone($mainAuthUser->getTimezone()),
                    (new \DateTimeImmutable())
                        ->setTimezone($mainAuthUser->getTimezone())
                        ->setTime(0, 0, 0),
                    $mainUser,
                );

                foreach ($habitItem['weekdays'] as $weekday) {
                    $habitWeekday = new HabitWeekday(
                        new Ulid(),
                        $habit,
                        $weekday
                    );

                    $manager->persist($habitWeekday);
                }

                $manager->persist($habit);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
