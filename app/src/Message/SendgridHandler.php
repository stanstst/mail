<?php

namespace App\Message;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendgridHandler implements MessageHandlerInterface
{
    public function __invoke(EmailCreatedMessage $message)
    {
        if ($message->isHandled()) {

            return;
        }

        $message->markAsHandled();
    }
}