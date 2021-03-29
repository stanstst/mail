<?php

namespace App\Service;

use App\Dto\MailResponseDto;
use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailCreator
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

    public function create(string $content): MailResponseDto
    {
        /** @var Email $email */
        $email = $this->serializer->deserialize($content, Email::class, 'json');

        $errors = $this->validator->validate($email);

        $responseErrors = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $responseErrors[$error->getPropertyPath()] = $error->getMessage();
        }

        $this->entityManager->persist($email);
        $this->entityManager->flush();

        return new MailResponseDto($email->getId(), $responseErrors);
    }

}