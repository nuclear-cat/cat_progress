<?php declare(strict_types=1);

namespace App\Model;

abstract class AbstractEnum
{
    protected string|int $value;
    protected string     $name;

    public function __construct($value)
    {
        $constName = array_search($value, self::getAll());
        if (!$constName) {
            throw new \InvalidArgumentException('Undefined enum value ' . $value);
        }

        $this->value = $value;
        $this->name  = $constName;
    }

    public static function getAll(): array
    {
        return (new \ReflectionClass(static::class))->getConstants();
    }

    /**
     * @return static[]
     */
    public static function createAll(): array
    {
        $values = [];

        foreach (self::getAll() as $value) {
            $values[] = new static($value);
        }

        return $values;
    }

    public function getValue(): string|int
    {
        return $this->value;
    }

    public function getConstName(): string
    {
        return $this->name;
    }

    public static function valueIsExists(string|int $value): bool
    {
        if (in_array($value, self::getAll())) {
            return true;
        }

        return false;
    }
}
