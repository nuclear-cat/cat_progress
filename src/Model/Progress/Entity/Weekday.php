<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

enum Weekday: string
{
    case Monday = 'Monday';
    case Tuesday = 'Tuesday';
    case Wednesday = 'Wednesday';
    case Thursday = 'Thursday';
    case Friday = 'Friday';
    case Saturday = 'Saturday';
    case Sunday = 'Sunday';

    public function getShort(): string
    {
        return match ($this) {
            self::Monday => 'Mon',
            self::Tuesday => 'Tue',
            self::Wednesday => 'Wed',
            self::Thursday => 'Thu',
            self::Friday => 'Fri',
            self::Saturday => 'Sat',
            self::Sunday => 'Sun',
        };
    }
}
