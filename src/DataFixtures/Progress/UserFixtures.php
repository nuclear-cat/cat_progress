<?php declare(strict_types=1);

namespace App\DataFixtures\Progress;

use App\Model\Progress\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use App\Model\User\Entity\Email;
use App\Model\User\Service\PasswordHasher;

class UserFixtures extends Fixture
{
    public const PROGRESS_MAIN_USER_REF = 'ref.progress.user.main';
    public const AUTH_MAIN_USER_REF = 'ref.auth.user.main';

    public const PROGRESS_MEMBER_USER_REF = 'ref.progress.user.member';
    public const AUTH_MEMBER_USER_REF = 'ref.auth.user.member';

    private PasswordHasher $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $mainUserUlid = new Ulid();
        $mainAuthUser = new \App\Model\User\Entity\User(
            $mainUserUlid,
            new Email('admin@app.test'),
            $this->hasher->hash('password'),
            'Ilya',
            new \DateTimeZone('Asia/Barnaul'),
        );
        $mainAuthUser->activate();
        $manager->persist($mainAuthUser);
        $mainUser = new User($mainUserUlid, 'Main User');
        $manager->persist($mainUser);

        $memberUserUlid = new Ulid();
        $memberAuthUser = new \App\Model\User\Entity\User(
            $memberUserUlid,
            new Email('member@app.test'),
            $this->hasher->hash('password'),
            'Ilya',
            new \DateTimeZone('Asia/Barnaul'),
        );
        $memberAuthUser->activate();
        $manager->persist($memberAuthUser);
        $memberUser = new User($memberUserUlid, 'Main User');
        $manager->persist($memberUser);

        $this->addReference(self::PROGRESS_MAIN_USER_REF, $mainUser);
        $this->addReference(self::AUTH_MAIN_USER_REF, $mainAuthUser);

        $this->addReference(self::PROGRESS_MEMBER_USER_REF, $memberUser);
        $this->addReference(self::AUTH_MEMBER_USER_REF, $memberAuthUser);

        $manager->flush();
    }
}
