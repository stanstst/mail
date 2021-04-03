<?php

namespace App\Message;

use App\Repository\EmailRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

abstract class MailMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EmailRepository
     */
    private $emailRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SendRequestInterface
     */
    private $request;

    public function __construct(EmailRepository $emailRepository, SendRequestInterface $request, LoggerInterface $logger)
    {
        $this->emailRepository = $emailRepository;
        $this->logger = $logger;
        $this->request = $request;
    }

    public function __invoke(EmailCreatedMessage $message)
    {
        if ($message->isHandled()) {

            return;
        }

        try {
            $email = $this->emailRepository->find($message->getEmailId());
            $isSuccessful = $this->request->send($email);
            if ($isSuccessful) {
                $message->markAsHandled();
            }
        } catch (Throwable $e) {
            $this->logger->error(sprintf('Sending mail failed %s', $message->getEmailId()));
        }
    }

}