<?php

namespace App\Command;

use App\Database\DBO\DataBaseManagementObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SimpleFixtures extends Command
{
    protected static $defaultName = 'app:fixtures:load';
    protected static $description = "Fills the database with random data for test purpose.";

    private DataBaseManagementObject $dbmo;
    
    public function __construct(DataBaseManagementObject $dbmo)
    {
        $this->dbmo = $dbmo;
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln("");
        $output->writeln("<comment>Fixtures loading</comment>");

        $result = $this->dbmo->loadFixtures();

        if ($result === null) {
            $io->error("Cannot establish a connection to the database server. Check your .env file");

            return Command::FAILURE;
        }

        if ($result === false) {
            $io->error("SQL error while executing the query.");

            return Command::FAILURE;
        }

        if ($result >= 0) {
            $io->success("Fixtures loaded");
        }

        return Command::SUCCESS;
    }
}
