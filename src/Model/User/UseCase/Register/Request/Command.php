<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Register\Request;

class Command
{
    public string $email;
    public string $name;
    public string $password;
    public string $timezone;
    public ?string $target = null;
}
