<?php declare(strict_types=1);

namespace App\Controller\Cabinet\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route('/cabinet/user/settings', name: 'cabinet.user.settings', methods: ['GET', 'POST'])]
    public function settings()
    {

    }
}