<?php declare(strict_types=1);

namespace App\Model\User\Entity;

enum Status: string
{
    case Active = 'active';
    case Wait = 'wait';
    case Banned = 'banned';
}
