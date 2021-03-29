<?php

namespace App\Controller;

use App\Service\EmailCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class MailController extends AbstractController
{
    /**
     * @var EmailCreator
     */
    private $emailCreator;

    public function __construct(EmailCreator $emailCreator)
    {

        $this->emailCreator = $emailCreator;
    }

    /**
     * @Route(methods={"post"}, "/email/create", name="emailCreate")
     */
    public function create(Request $request): Response
    {
        try {
            $responseDto = $this->emailCreator->create($request->getContent());
        } catch (Throwable $e) {
            return new JsonResponse(['error' => 'internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (count($responseDto->errors)) {
            return new JsonResponse($responseDto, Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($responseDto);
    }
}
