<?php declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\ConfirmRequest;

class PasswordResetTokenGenerator
{
    public function generate(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
