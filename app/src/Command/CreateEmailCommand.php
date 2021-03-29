<?php

namespace App\Command;

use App\Service\EmailCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateEmailCommand extends Command
{
    const EMAIL_DATA_ARGUMENT = 'email-data';

    protected static $defaultName = 'app:create-email';

    /**
     * @var EmailCreator
     */
    private $emailCreator;

    protected function configure()
    {
        $this->setDescription('Adds new email entry and expects json argument');

        $this->setHelp('Example email data: "{ \"fromEmail\": \"stanstst@abv.bg\", \"fromName\": \"Stan Stefanov\", \"subject\": \"email subject\", \"recipients\": [ {\"Stanimir\": \"stan.r.stefanov@gmail.com\"}, {\"Stanimirrrr\": \"stannnn.r.stefanov@gmail.com\"} ], \"textPart\": \"Dear passenger, welcome to Mail\", \"htmlPart\": \"<h3>Dear passenger, welcome to Mail<h3>\" }"');

        $this->addArgument(self::EMAIL_DATA_ARGUMENT, InputArgument::REQUIRED, 'email data');
    }

    public function __construct(EmailCreator $emailCreator, string $name = null)
    {
        parent::__construct($name);

        $this->emailCreator = $emailCreator;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @todo Validate json or implement another UI input

        try {
            $resultDto = $this->emailCreator->create($input->getArgument(self::EMAIL_DATA_ARGUMENT));
        } catch (Throwable $e) {
            $output->writeln(sprintf('Error creating en email entry: %s', $e->getMessage()));

            return Command::FAILURE;
        }

        if (count($resultDto->errors)) {
            $output->writeln(sprintf('Error creating en email entry.'));
            $output->writeln(sprintf('Errors:'));
            $output->writeln(sprintf(print_r($resultDto->errors)));

            return Command::FAILURE;
        }

        $output->writeln(sprintf('Created email resourceId: %s', $resultDto->resourceId));

        return Command::SUCCESS;
    }
}