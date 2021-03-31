<?php

namespace App\Tests\Service;

use App\Service\EmailCreator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailCreatorTest extends TestCase
{

    /**
     * @var EmailCreator
     **/
    protected $object;

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

    public function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->validator = $this->prophesize(ValidatorInterface::class);
        $this->messageBus = $this->prophesize(MessageBusInterface::class);

        $this->object = new EmailCreator(
            $this->entityManager->reveal(),
            $this->serializer->reveal(),
            $this->validator->reveal(),
            $this->messageBus->reveal()
        );
    }

    public function testCreate()
    {

    }
}
