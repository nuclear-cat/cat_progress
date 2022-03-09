<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\Update;

use App\Model\Progress\Repository\Project\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private ProjectRepository     $projectRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get($command->id);

        $project
            ->setTitle($command->title)
            ->setColor($command->color)
            ->setDescription($command->description);

        $this->entityManager->flush();
    }
}
