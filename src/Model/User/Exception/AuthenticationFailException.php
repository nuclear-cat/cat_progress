<?php declare(strict_types=1);

namespace App\Model\User\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

class AuthenticationFailException extends AuthenticationException
{
    private string $reason;

    public function __construct(string $message, string $reason, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->reason = $reason;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
