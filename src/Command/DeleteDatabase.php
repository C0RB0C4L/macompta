<?php

namespace App\Command;

use App\Database\DBO\DataBaseManagementObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteDatabase extends Command
{
    protected static $defaultName = 'app:database:delete';
    protected static $description = "Deletes the database";

    private DataBaseManagementObject $dbmo;

    private const VALIDATION_ANSWER = [
        "y",
        "yes",
        "n",
        "no",
    ];

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
        $output->writeln("<comment>Database deletion '" . $this->dbmo->getDbName() . "'</comment>");

        $io->warning("The database will be deleted. Confirm if you know what you are doing.");

        $helper = $this->getHelper('question');
        $question = new Question('Are you sure ? (yes or no)', '');
        $question->setHidden(true);
        $question->setHiddenFallback(true);

        $question->setValidator(function (?string $answer): string {

            if (!is_string($answer) || !in_array($answer, self::VALIDATION_ANSWER)) {
                throw new \RuntimeException(
                    "Please answer yes or no."
                );
            }

            return $answer;
        });

        $answer = $helper->ask($input, $output, $question);

        if (str_contains($answer, "y")) {

            $result = $result = $this->dbmo->deleteDatabase();

            if ($result === null) {
                $io->error("Cannot establish a connection to the database server. Check your .env file");

                return Command::FAILURE;
            }

            if ($result === false) {
                $io->error("SQL error while executing the query. Is the password properly encoded ?");

                return Command::FAILURE;
            }

            if ($result >= 0) {
                $io->success("Database successfully deleted");
            }

            return Command::SUCCESS;
        } else {
            $output->writeln("");
            $output->writeln("<info>deletion aborted</info>");
            $output->writeln("");
            return Command::SUCCESS;
        }

        dump($answer);
        die;
    }
}
