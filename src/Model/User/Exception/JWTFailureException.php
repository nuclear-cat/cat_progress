<?php declare(strict_types=1);

namespace App\Model\User\Exception;

class JWTFailureException extends \Exception
{
    private string $reason;
    private ?array $payload;

    public function __construct(string $reason, string $message, \Throwable $previous = null, ?array $payload = null)
    {
        $this->reason = $reason;
        $this->payload = $payload;

        parent::__construct($message, 0, $previous);
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }
}
