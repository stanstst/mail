<?php

namespace App\Service;

use App\Dto\MailResponseDto;
use App\Entity\Email;
use App\Message\EmailCreatedMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
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

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        MessageBusInterface $messageBus
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->messageBus = $messageBus;
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

        if (count($responseErrors) === 0) {
            $this->entityManager->persist($email);
            $this->entityManager->flush();

            $this->messageBus->dispatch(new EmailCreatedMessage($email->getId()));
        }

        return new MailResponseDto($email->getId(), $responseErrors);
    }

}