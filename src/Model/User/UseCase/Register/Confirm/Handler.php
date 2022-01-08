<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Register\Confirm;

use App\Model\User\Repository\ConfirmRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class Handler
{
    private ConfirmRequestRepository $tokens;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ConfirmRequestRepository $tokens,
        EntityManagerInterface   $entityManager
    ) {
        $this->tokens = $tokens;
        $this->flusher = $entityManager;
    }

    public function handle(Command $command): void
    {
        $token = $this->tokens->findByUserIdAndToken($command->id, $command->confirmToken);

        if (!$token) {
            throw new BadCredentialsException('Invalid token.');
        }

        $token->getUser()->activate();
        $this->flusher->flush();
    }
}
