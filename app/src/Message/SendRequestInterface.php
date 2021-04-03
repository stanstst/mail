<?php

namespace App\Message;

use App\Entity\Email;

interface SendRequestInterface
{
    public function send(Email $email): bool;
}