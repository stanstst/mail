<?php

namespace App\Message;

class EmailCreatedMessage
{
    /**
     * @var int
     */
    private $emailId;

    /**
     * @var bool
     */
    private $isHandled = false;

    public function __construct(int $emailId)
    {
        $this->emailId = $emailId;
    }

    public function getEmailId(): int
    {
        return $this->emailId;
    }

    public function markAsHandled()
    {
        $this->isHandled = true;
    }

    public function isHandled(): bool
    {
        return $this->isHandled;
    }
}