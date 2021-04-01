<?php

namespace App\Message;

use App\Integration\MailjetClient;
use App\Integration\MailjetRequest;
use App\Repository\EmailRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class MailjetHandler implements MessageHandlerInterface
{
    /**
     * @var EmailRepository
     */
    private $emailRepository;

    /**
     * @var MailjetClient
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MailjetRequest
     */
    private $request;

    public function __construct(EmailRepository $emailRepository, MailjetRequest $request, LoggerInterface $logger)
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