<?php

declare(strict_types=1);

namespace Micro\User\Tests\Application\Query\Event\GetEvents;

use Micro\User\Application\Command\User\SignUp\SignUpCommand;
use Micro\User\Application\Query\Collection;
use Micro\User\Application\Query\Event\GetEvents\GetEventsQuery;
use Micro\User\Infrastructure\Share\Event\Consumer\SendEventsToElasticConsumer;
use Micro\User\Infrastructure\Share\Event\Query\EventElasticRepository;
use Micro\User\Tests\Application\ApplicationTestCase;
use Micro\User\Tests\Infrastructure\Share\Event\Publisher\InMemoryProducer;
use Ramsey\Uuid\Uuid;

final class GetEventsTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function processedEventsMustBeInElasticSearchTest(): void
    {
        $response = $this->ask(new GetEventsQuery());

        self::assertInstanceOf(Collection::class, $response);
        self::assertSame(1, $response->total);
        self::assertSame('Micro.User.Domain.User.Event.UserWasCreated', $response->data[0]['type']);
    }

    /**
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->service('events_repository');
        $eventReadStore->delete();

        /** @var InMemoryProducer $consumersRegistry */
        $consumersRegistry = $this->service(InMemoryProducer::class);
        /** @var SendEventsToElasticConsumer $consumer */
        $consumer = $this->service('events_to_elastic');
        $consumersRegistry->addConsumer('Micro.User.Domain.User.Event.UserWasCreated', $consumer);

        $command = new SignUpCommand(
            Uuid::uuid4()->toString(),
            'asd@asd.asd',
            'qwerqwer'
        );
        $this->handle($command);

        $this->fireTerminateEvent();

        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->service('events_repository');
        $eventReadStore->refresh();
    }

    protected function tearDown()
    {
        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->service('events_repository');
        $eventReadStore->delete();

        parent::tearDown();
    }
}
