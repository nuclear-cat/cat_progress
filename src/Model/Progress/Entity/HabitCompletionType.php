<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

enum HabitCompletionType: string
{
    case Success = 'success';
    case Partially = 'partially';
    case Lazy = 'lazy';
    case Alternative = 'alternative';
    case Failed = 'failed';
}
