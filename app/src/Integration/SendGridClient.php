<?php

namespace App\Integration;

use Psr\Log\LoggerInterface;
use SendGrid;
use SendGrid\Mail\Mail;
use SendGrid\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

class SendGridClient
{
    /**
     * @var SendGrid
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger)
    {
        $apikey = $parameterBag->get('app.sg.key');
        $this->client = new SendGrid($apikey);
        $this->logger = $logger;
    }

    public function send(Mail $emailPayload): Response
    {
        $response = new Response(400);
        try {
            $response = $this->client->send($emailPayload);
        } catch (Throwable $e) {
            $this->logger->info(sprintf('SendGridClient error: %s', $e->getMessage()));
        }

        return $response;
    }
}