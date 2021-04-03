<?php

namespace App\Message;

use App\Entity\Email;

class SendgridRequest implements SendRequestInterface
{

    public function send(Email $email): bool
    {
        return false;
    }
}