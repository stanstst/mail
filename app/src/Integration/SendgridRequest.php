<?php

namespace App\Integration;

use App\Entity\Email;
use App\Message\SendRequestInterface;
use SendGrid\Mail\Mail;

class SendgridRequest implements SendRequestInterface
{
    /**
     * @var SendGridClient
     */
    private $client;

    public function __construct(SendGridClient $client)
    {
        $this->client = $client;
    }

    public function send(Email $email): bool
    {

        $emailPayload = new Mail();
        $emailPayload->setFrom($email->getFromEmail(), $email->getFromName());
        $emailPayload->setSubject($email->getSubject());
        $emailPayload->addContent("text/plain", $email->getTextPart());
        $emailPayload->addContent(
            "text/html", $email->getHtmlPart());

        $this->addRecipients($email->getRecipients(), $emailPayload);

        $response = $this->client->send($emailPayload);
        return $response->statusCode() === 200;
    }

    private function addRecipients(array $recipients, Mail $mailPayload): void
    {
        foreach ($recipients as $email) {
            $mailPayload->addTo($email['email'], $email['name']);
        }
    }
}