<?php

namespace App\Command;

use App\Database\DBO\DataBaseManagementObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateSchema extends Command
{
    protected static $defaultName = 'app:schema:create';
    protected static $description = "Creates the DB schema according to the method defined in DataBaseManagementObject";

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
        $output->writeln("<comment>Database schema creation '" . $this->dbmo->getDbName() . "'</comment>");
        
        $result = $this->dbmo->createTables();

        if ($result >= 0) {
            $io->success("Schema successfully created.");
        } else {
            $io->error("Error while creating schema.");
        }

        return Command::SUCCESS;
    }
}
