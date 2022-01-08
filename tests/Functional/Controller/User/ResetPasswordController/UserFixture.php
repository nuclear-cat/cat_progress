<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller\User\ResetPasswordController;

use App\Model\User\Entity\Email;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class UserFixture extends Fixture
{
    private PasswordHasher $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new \App\Model\User\Entity\User(
            new Ulid(),
            new Email('donald.trump@app.test'),
            $this->hasher->hash('password'),
            'Donald Trump',
            new \DateTimeZone('America/New_York'),
        );
        $user->activate();
        $manager->persist($user);

        $manager->flush();
    }
}
