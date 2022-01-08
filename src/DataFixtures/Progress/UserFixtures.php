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

        $this->addReference(self::PROGRESS_MAIN_USER_REF, $mainUser);
        $this->addReference(self::AUTH_MAIN_USER_REF, $mainAuthUser);

        $manager->persist($mainUser);
        $manager->flush();
    }
}
