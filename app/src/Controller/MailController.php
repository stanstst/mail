<?php

namespace App\Controller;

use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MailController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(methods={"post"}, "/email/create", name="emailCreate")
     */
    public function create(Request $request): Response
    {

        /** @var Email $email */
        $email = $this->serializer->deserialize($request->getContent(), Email::class, 'json');

        $errors = $this->validator->validate($email);

        $responseErrors = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $responseErrors[$error->getPropertyPath()] = $error->getMessage();
        }

        $this->entityManager->persist($email);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'errors' => $responseErrors,
                'resourceId' => $email->getId(),
            ]
        );
    }
}
