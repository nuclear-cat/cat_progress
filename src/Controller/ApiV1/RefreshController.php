<?php declare(strict_types=1);

namespace App\Controller\ApiV1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RefreshController extends AbstractController
{
    #[Route('/api/v1/refresh', name: 'api.v1.refresh', methods: ['POST'])]
    public function overview(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
