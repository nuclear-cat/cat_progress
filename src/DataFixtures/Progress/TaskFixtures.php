<?php declare(strict_types=1);

namespace App\DataFixtures\Progress;

use App\Model\Progress\Entity\Task;
use App\Model\Progress\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $mainUser */
        $mainUser = $this->getReference(UserFixtures::PROGRESS_MAIN_USER_REF);

        $items = [
            ['title' => 'Study RxJS - 10 minutes',],
            ['title' => 'Study Angular - 10 minutes',],
            ['title' => 'Any sport task',],
            ['title' => 'CatProgress: Add creating habits',],
            ['title' => 'CatProgress: Access to habits only for user',],
            ['title' => 'Do leg workout with expander - 4 sets',],
            ['title' => 'Any task',],
        ];

        foreach ($items as $item) {
            $task = new Task(
                new Ulid(),
                $item['title'],
                new \DateTimeImmutable(),
                $mainUser,
            );

            $manager->persist($task);
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
