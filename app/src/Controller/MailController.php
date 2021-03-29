<?php

namespace App\Controller;

use App\Service\EmailCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @var EmailCreator
     */
    private $emailCreator;

    public function __construct(EmailCreator $emailCreator) {

        $this->emailCreator = $emailCreator;
    }

    /**
     * @Route(methods={"post"}, "/email/create", name="emailCreate")
     */
    public function create(Request $request): Response
    {
        $responseDto = $this->emailCreator->create($request->getContent());

        return new JsonResponse($responseDto);
    }
}
