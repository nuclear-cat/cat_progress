<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ChangeEmail\Request;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @see Assert
 */
class Command
{
    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    public string $email;

    public Ulid $userId;
}
