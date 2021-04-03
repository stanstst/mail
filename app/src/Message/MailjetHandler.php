<?php

namespace App\Message;

use App\Integration\MailjetRequest;
use App\Repository\EmailRepository;
use Psr\Log\LoggerInterface;

class MailjetHandler extends MailMessageHandler
{

    public function __construct(EmailRepository $emailRepository, MailjetRequest $request, LoggerInterface $logger)
    {
        parent::__construct($emailRepository, $request, $logger);
    }

}