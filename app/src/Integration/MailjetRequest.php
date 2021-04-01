<?php

namespace App\Integration;

use App\Entity\Email;

class MailjetRequest
{
    /**
     * @var MailjetClient
     */
    private $client;

    public function __construct(MailjetClient $client)
    {
        $this->client = $client;
    }

    public function send(Email $email): bool
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $email->getFromEmail(),
                        'Name' => $email->getFromName()
                    ],
                    'To' => $this->formatRecipients($email->getRecipients()),
                    'Subject' => $email->getSubject(),
                    'TextPart' => $email->getTextPart(),
                    'HTMLPart' => $email->getHtmlPart(),
                ]
            ]
        ];

        return $this->client->send($body)->success();
    }

    private function formatRecipients(array $recipients): array
    {
        $requestRecipients = [];
        foreach ($recipients as $email) {
            $requestRecipients[] = ['Name' => $email['name'], 'Email' => $email['email']];
        }
        return $requestRecipients;
    }
}