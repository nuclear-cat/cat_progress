<?php declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class CheckDbConnectionCommand extends Command
{
    protected static $defaultName = 'app:check-db-connection';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            if ($this->entityManager->getConnection()->connect()) {
                $output->writeln('Connection successful!');

                return Command::SUCCESS;
            }
        } catch (\Exception) {
        }

        $output->writeln('No connection');

        return Command::FAILURE;
    }
}
