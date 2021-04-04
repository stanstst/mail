<?php

namespace App\Message;

use App\Integration\SendgridRequest;
use App\Repository\EmailRepository;
use Psr\Log\LoggerInterface;

class SendgridHandler extends MailMessageHandler
{

    public function __construct(EmailRepository $emailRepository, SendgridRequest $request, LoggerInterface $logger)
    {
        parent::__construct($emailRepository, $request, $logger);
    }
}