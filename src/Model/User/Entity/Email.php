<?php declare(strict_types=1);

namespace App\Model\User\Entity;

use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);

        $this->value = strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
