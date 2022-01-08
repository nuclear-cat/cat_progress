<?php declare(strict_types=1);

namespace App\Controller\ApiV1;

use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method UserIdentity getUser()
 */
class LoginController extends AbstractController
{
    #[Route('/api/v1/login', name: 'api.v1.login', methods: ['POST'])]
    public function overview(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
