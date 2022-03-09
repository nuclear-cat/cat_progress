<?php declare(strict_types=1);

namespace App\Model\Progress\Service;

class InviteTokenGenerator
{
    public function generate(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
