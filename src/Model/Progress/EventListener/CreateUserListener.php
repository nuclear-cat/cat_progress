<?php declare(strict_types=1);

namespace App\Model\Progress\EventListener;

use App\Event\UserCreatedEvent;
use App\Model\Progress\UseCase\User\Create\Command;
use App\Model\Progress\UseCase\User\Create\Handler;

class CreateUserListener
{
    public function __construct(private Handler $handler)
    {
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $command = new Command();
        $command->id = $event->getUserId();
        $command->name = $event->getName();

        $this->handler->handle($command);
    }
}
