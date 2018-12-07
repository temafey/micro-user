<?php

declare(strict_types=1);

namespace Micro\User\Tests\UI\Http\Rest\Controller\Events;

use Micro\User\Infrastructure\Share\Event\Consumer\SendEventsToElasticConsumer;
use Micro\User\Infrastructure\Share\Event\Query\EventElasticRepository;
use Micro\User\Tests\Infrastructure\Share\Event\Publisher\InMemoryProducer;
use Micro\User\Tests\UI\Http\Rest\Controller\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetEventsControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function eventsListMustReturn404WhenNoPageFoundTest(): void
    {
        $this->get('/api/events?page=100');

        self::assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function eventsShouldBePresentInElasticSearchTest(): void
    {
        $this->refreshIndex();
        $this->get('/api/events', ['limit' => 1]);

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $responseDecoded = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(1, $responseDecoded['meta']['total']);
        self::assertSame(1, $responseDecoded['meta']['page']);
        self::assertSame(1, $responseDecoded['meta']['size']);
        self::assertSame('Micro.User.Domain.User.Event.UserWasCreated', $responseDecoded['data'][0]['type']);
        self::assertSame(self::DEFAULT_EMAIL, $responseDecoded['data'][0]['payload']['credentials']['email']);
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function givenInvalidPageReturns400StatusTest(): void
    {
        $this->get('/api/events?page=two');

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function givenInvalidLimitReturns400StatusTest(): void
    {
        $this->get('/api/events?limit=three');

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    private function refreshIndex(): void
    {
        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->client->getContainer()->get('events_repository');
        $eventReadStore->refresh();
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->client->getContainer()->get('events_repository');
        $eventReadStore->boot();

        /** @var InMemoryProducer $consumersRegistry */
        $consumersRegistry = $this->client->getContainer()->get(InMemoryProducer::class);
        /** @var SendEventsToElasticConsumer $consumer */
        $consumer = $this->client->getContainer()->get('events_to_elastic');
        $consumersRegistry->addConsumer('Micro.User.Domain.User.Event.UserWasCreated', $consumer);

        $this->refreshIndex();

        $this->createUser();
        $this->auth();
    }

    protected function tearDown()
    {
        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->client->getContainer()->get('events_repository');
        $eventReadStore->delete();

        parent::tearDown();
    }
}
