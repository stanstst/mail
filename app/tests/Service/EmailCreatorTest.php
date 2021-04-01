<?php

namespace App\Tests\Service;

use App\Dto\MailResponseDto;
use App\Entity\Email;
use App\Message\EmailCreatedMessage;
use App\Service\EmailCreator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailCreatorTest extends TestCase
{
    const EMAIL_REQUEST_DATA = '{email-data}';
    const CREATED_EMAIL_ID = 123;
    const ERROR_MESSAGE = 'Error message';
    const PROP_PATH = 'prop.path';

    /**
     * @var EmailCreator
     **/
    protected $emailCreator;

    /**
     * @var EntityManagerInterface|\Prophecy\Prophecy\ObjectProphecy
     */
    private $entityManager;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy|SerializerInterface
     */
    private $serializer;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy|ValidatorInterface
     */
    private $validator;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy|MessageBusInterface
     */
    private $messageBus;

    /**
     * @var Email|\Prophecy\Prophecy\ObjectProphecy
     */
    private $emailEntity;

    /**
     * @var ConstraintViolationList
     */
    private $emailValidationErrors;

    public function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->validator = $this->prophesize(ValidatorInterface::class);
        $this->messageBus = $this->prophesize(MessageBusInterface::class);

        $this->emailEntity = $this->prophesize(Email::class);
        $this->emailValidationErrors = new ConstraintViolationList();

        $this->emailCreator = new EmailCreator(
            $this->entityManager->reveal(),
            $this->serializer->reveal(),
            $this->validator->reveal(),
            $this->messageBus->reveal()
        );
    }

    public function testCreatePersistsNewEmailWhenNoErrors()
    {
        $this->serializer->deserialize(self::EMAIL_REQUEST_DATA, Email::class, 'json')->willReturn($this->emailEntity);

        $this->validator->validate($this->emailEntity)->willReturn($this->emailValidationErrors);

        $this->emailEntity->getId()->willReturn(self::CREATED_EMAIL_ID);

        $this->entityManager->persist($this->emailEntity)->shouldBeCalledOnce();
        $this->entityManager->flush()->shouldBeCalledOnce();
        $this->messageBus->dispatch(new EmailCreatedMessage(self::CREATED_EMAIL_ID))
            ->shouldBeCalledOnce()
            ->willReturn(new Envelope(new EmailCreatedMessage(self::CREATED_EMAIL_ID)));

        $result = $this->emailCreator->create(self::EMAIL_REQUEST_DATA);
        $this->assertEquals(new MailResponseDto(self::CREATED_EMAIL_ID, []), $result);
    }

    public function testCreateDoesNotPersistsAnEmailWhenThereAreErrors()
    {
        $this->serializer->deserialize(self::EMAIL_REQUEST_DATA, Email::class, 'json')->willReturn($this->emailEntity);

        $constraintViolation = new ConstraintViolation(
            self::ERROR_MESSAGE,
            '',
            [], 'root',
            self::PROP_PATH,
            'invalid-value'
        );
        $this->emailValidationErrors->add($constraintViolation);
        $this->validator->validate($this->emailEntity)->willReturn($this->emailValidationErrors);

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();
        $this->messageBus->dispatch(Argument::any())
            ->shouldNotBeCalled();

        $result = $this->emailCreator->create(self::EMAIL_REQUEST_DATA);
        $this->assertEquals(new MailResponseDto(null, [self::PROP_PATH => self::ERROR_MESSAGE]), $result);
    }
}
