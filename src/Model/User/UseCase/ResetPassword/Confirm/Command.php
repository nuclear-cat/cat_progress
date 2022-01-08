<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @see Assert
 */
class Command
{
    public string $token;

    /**
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    public string $password;

    /**
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    public string $repeatPassword;
}
