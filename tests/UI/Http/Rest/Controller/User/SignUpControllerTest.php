<?php

namespace Micro\User\Tests\UI\Http\Rest\Controller\User;

use Broadway\Domain\DomainMessage;
use Micro\User\Domain\User\Event\UserWasCreated;
use Micro\User\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Micro\User\Tests\UI\Http\Rest\Controller\JsonApiTestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class SignUpControllerTest extends JsonApiTestCase
{
    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createUser();
        $this->auth();
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function givenValidUuidAndEmailShouldReturn201StatusCodeTest(): void
    {
        $this->post('/api/users', [
            'uuid'     => Uuid::uuid4()->toString(),
            'email'    => 'jo@jo.com',
            'password' => 'oaisudaosudoaudo',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $eventCollector->popEvents();

        self::assertCount(1, $events);

        $userWasCreatedEvent = $events[0];

        self::assertInstanceOf(UserWasCreated::class, $userWasCreatedEvent->getPayload());
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function invalidInputParametersShouldReturn400StatusCodeTest(): void
    {
        $this->post('/api/users', [
            'uuid'  => Uuid::uuid4()->toString(),
            'email' => 'invalid email',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);
        $events = $eventCollector->popEvents();

        self::assertCount(0, $events);
    }
}
