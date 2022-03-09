<?php declare(strict_types=1);

namespace App\Model\Progress\ValueObject;

enum Color: string
{
    case Red = '#e74856';
    case Orange = '#ff8c00';
    case Peach = '#ffab45';
    case Yellow = '#fff100';
    case Green = '#47d041';
    case LightBlueGreen = '#30c6cc';
    case Olive = '#73aa23';
    case Blue = '#00bcf2';
    case Lilac = '#8764b8';
    case Pink = '#f495bf';
    case LightMetallic = '#a0aeb2';
    case DarkMetallic = '#004b60';
    case LightGrey = '#b1adab';
    case Grey = '#5d5a58';
    case Black = '#000000';
    case DarkRed = '#750b1c';
    case DarkOrange = '#ca500f';
    case Brown = '#ab620c';
    case DarkYellow = '#c19c00';
    case DarkGreen = '#004b1c';
    case DarkBlueGreen = '#004b50';
    case DarkOlive = '#0b6a0b';
    case DarkBlue = '#002050';
    case DarkLilac = '#32145a';
    case DarkPurple = '#5c005c';

    public function getName(): string
    {
        return match($this)
        {
            self::Red => 'red',
            self::Orange => 'orange',
            self::Peach => 'peach',
            self::Yellow => 'yellow',
            self::Green => 'green',
            self::LightBlueGreen => 'Light blue-green',
            self::Olive => 'olive',
            self::Blue => 'blue',
            self::Lilac => 'lilac',
            self::Pink => 'pink',
            self::LightMetallic => 'light metallic',
            self::DarkMetallic => 'dark metallic',
            self::LightGrey => 'light grey',
            self::Grey => 'grey',
            self::Black => 'black',
            self::DarkRed => 'dark red',
            self::DarkOrange => 'dark orange',
            self::Brown => 'brown',
            self::DarkYellow => 'dark yellow',
            self::DarkGreen => 'dark green',
            self::DarkBlueGreen => 'dark blue-green',
            self::DarkOlive => 'dark olive',
            self::DarkBlue => 'dark blue',
            self::DarkLilac => 'dark lilac',
            self::DarkPurple => 'dark purple',
        };
    }
}
