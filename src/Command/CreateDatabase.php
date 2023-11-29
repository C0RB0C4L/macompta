<?php

namespace App\Command;

use App\Database\DBO\DataBaseManagementObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateDatabase extends Command
{
    protected static $defaultName = 'app:database:create';
    protected static $description = "Creates the database according to the parameters in the .env file.";

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
        $output->writeln("<comment>Database creation '" . $this->dbmo->getDbName() . "'</comment>");

        $result = $this->dbmo->createDatabase();

        if ($result === null) {
            $io->error("Cannot establish a connection to the database server. Check your .env file");

            return Command::FAILURE;
        }

        if ($result === false) {
            $io->error("SQL error while executing the query. Is the password properly encoded ?");

            return Command::FAILURE;
        }

        if ($result > 0) {
            $io->success("Database successfully created");
        }

        if ($result === 0) {
            $io->warning("The database already exists");
        }

        return Command::SUCCESS;
    }
}
