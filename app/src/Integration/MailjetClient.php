<?php

namespace App\Integration;

use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailjetClient
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $apikey = $parameterBag->get('app.mj.public');
        $apiSecret = $parameterBag->get('app.mj.secret');
        $this->client = new Client($apikey, $apiSecret, true, ['version' => 'v3.1']);
    }

    public function send($body): Response
    {
        return $this->client->post(Resources::$Email, ['body' => $body]);
    }
}