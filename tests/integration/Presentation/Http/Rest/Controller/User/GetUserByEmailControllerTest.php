<?php

declare(strict_types=1);

namespace Micro\User\Tests\Integration\Presentation\Http\Rest\Controller\User;

use Micro\User\Tests\Integration\Presentation\Http\Rest\Controller\JsonApiTestCase;
use Micro\User\Tests\Unit\Infrastructure\Share\Event\EventCollectorListener;
use Symfony\Component\HttpFoundation\Response;

class GetUserByEmailControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalidInputParametersShouldReturn400StatusCodeTest(): void
    {
        $this->createUser();
        $this->auth();

        $this->get('/api/user/asd@');

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);

        $events = $eventCollector->popEvents();

        self::assertCount(0, $events);
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Assert\AssertionFailedException
     */
    public function validInputParametersShouldReturn404StatusCodeWhenNotExistTest(): void
    {
        $this->createUser();
        $this->auth();

        $this->get('/api/user/asd@asd.asd');

        self::assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);

        $events = $eventCollector->popEvents();

        self::assertCount(0, $events);
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Assert\AssertionFailedException
     */
    public function validInputParametersShouldReturn200StatusCodeWhenExistTest(): void
    {
        $emailString = $this->createUser();
        $this->auth();

        $this->get('/api/user/' . $emailString);

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->client->getContainer()->get(EventCollectorListener::class);

        $events = $eventCollector->popEvents();

        self::assertCount(0, $events);
    }
}
