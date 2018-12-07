<?php

declare(strict_types=1);

namespace Micro\User\Tests\Infrastructure\Share\Event\Publisher;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Micro\User\Domain\User\Event\UserWasCreated;
use Micro\User\Infrastructure\Share\Event\Publisher\AsyncEventPublisher;
use Micro\User\Infrastructure\Share\Event\Publisher\EventPublisher;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class EventPublisherTest extends TestCase
{
    /** @var Consumer|null */
    private $consumer;

    /** @var EventPublisher|null */
    private $publisher;

    protected function setup()
    {
        $producer = new InMemoryProducer();

        $this->publisher = new AsyncEventPublisher(
            $producer
                ->addConsumer(
                    'Micro.User.Domain.User.Event.UserWasCreated',
                    $this->createConsumer()
                )
        );
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function messagesAreConsumedByRoutingKeyTest(): void
    {
        $data = ['uuid' => $uuid = Uuid::uuid4()->toString(), 'credentials' => ['email' => 'lol@lol.com', 'password' => 'lkasjbdalsjdbalsdbaljsdhbalsjbhd987']];

        $this->publisher->handle(
            new DomainMessage(
                $uuid,
                1,
                new Metadata(),
                UserWasCreated::deserialize($data),
                DateTime::now()
            )
        );

        $this->publisher->publish();

        /** @var UserWasCreated $event */
        $event = $this->consumer->getMessage()->getPayload();

        self::assertInstanceOf(UserWasCreated::class, $event);

        self::assertSame($data, $event->serialize(), 'Check that its the same event');
    }

    private function createConsumer(): Consumer
    {
        return $this->consumer = new Consumer();
    }

    protected function tearDown()
    {
        $this->publisher = null;
        $this->consumer = null;
    }
}

class Consumer implements ConsumerInterface
{
    /** @var DomainMessage|null */
    private $message;

    public function getMessage(): ?DomainMessage
    {
        return $this->message;
    }

    public function execute(AMQPMessage $msg)
    {
        $this->message = unserialize($msg->body);
    }
}
