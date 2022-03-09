<?php declare(strict_types=1);

namespace App\DataFixtures\Progress;

use App\Model\Progress\Entity\Project\Membership;
use App\Model\Progress\Entity\Project\MembershipPermission;
use App\Model\Progress\Entity\Project\Permission;
use App\Model\Progress\Entity\Project\Project;
use App\Model\Progress\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $mainUser */
        $mainUser = $this->getReference(UserFixtures::PROGRESS_MAIN_USER_REF);

        /** @var User $memberUser */
        $memberUser = $this->getReference(UserFixtures::PROGRESS_MEMBER_USER_REF);

        $projectsData = [
            ['title' => 'Cat Progress', 'id' => '00000000-0000-0000-0000-84c60b2ad693',],
            ['title' => 'Cat Meeting', 'id' => '00000000-0000-0000-0000-d3ef22a7b47c',],
        ];

        foreach ($projectsData as $item) {
            $project = new Project(
                Ulid::fromString($item['id']),
                $item['title'],
                $mainUser,
            );

            $manager->persist($project);
        }

        $memberProjectsData = [
            ['title' => 'Member project', 'id' => '00000000-0000-0000-0000-8f9e9ea2b4a7',],
        ];

        foreach ($memberProjectsData as $item) {
            $project = new Project(
                Ulid::fromString($item['id']),
                $item['title'],
                $memberUser,
            );

            $membership = new Membership(
                new Ulid(),
                $mainUser,
                $project,
                new \DateTimeImmutable(),
            );

            foreach (Permission::cases() as $permission) {
                $membershipPermission = new MembershipPermission(
                    new Ulid(),
                    $membership,
                    $permission,
                );
                $manager->persist($membershipPermission);
            }

            $manager->persist($membership);
            $manager->persist($project);
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
