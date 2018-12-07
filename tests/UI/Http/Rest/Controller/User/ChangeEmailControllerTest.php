<?php

declare(strict_types=1);

namespace Micro\User\Tests\UI\Http\Rest\Controller\User;

use Broadway\Domain\DomainMessage;
use Micro\User\Domain\User\Event\UserEmailChanged;
use Micro\User\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Micro\User\Tests\UI\Http\Rest\Controller\JsonApiTestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class ChangeEmailControllerTest extends JsonApiTestCase
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
    public function givenValidUuidAndEmailShouldReturnA201StatusCodeTest(): void
    {
        $this->post('/api/users/' . $this->userUuid->toString() . '/email', [
            'email' => 'weba@jo.com',
        ]);

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $eventCollector->popEvents();

        self::assertInstanceOf(UserEmailChanged::class, $events[0]->getPayload());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function givenValidUuidAndEmailUserShouldNotChangeOthersEmailAndGets401()
    {
        $this->post('/api/users/' . Uuid::uuid4()->toString() . '/email', [
            'email' => 'weba@jo.com',
        ]);

        self::assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $eventCollector->popEvents();

        self::assertCount(0, $events);
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function givenInvalidEmailShouldReturnA400StatusCodeTest(): void
    {
        $this->post('/api/users/' . $this->userUuid->toString() . '/email', [
            'email' => 'webajo.com',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
