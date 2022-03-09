<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\ChangeColor;

use App\Model\Progress\Repository\Project\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private ProjectRepository      $projectRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get($command->id);

        $project
            ->setColor($command->color);

        $this->entityManager->flush();
    }
}
