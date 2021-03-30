<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotSentEmailHandler implements MessageHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(EmailCreatedMessage $message)
    {
        if ($message->isHandled()) {

            return;
        }

        $this->logger->emergency(sprintf('Email was not sent, emailId: %s', $message->getEmailId()));
    }

}