<?php

declare(strict_types=1);

namespace Micro\User\Tests\Unit\Application\Command\User\SignUp;

use Broadway\Domain\DomainMessage;
use Micro\User\Application\Command\User\SignUp\SignUpCommand;
use Micro\User\Domain\Event\UserWasCreated;
use Micro\User\Tests\Unit\Application\ApplicationTestCase;
use Micro\User\Tests\Unit\Infrastructure\Share\Event\EventCollectorListener;
use Ramsey\Uuid\Uuid;

class SignUpHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function commandHandlerMustFireDomainEventTest(): void
    {
        $uuid = Uuid::uuid4();
        $email = 'asd@asd.asd';

        $command = new SignUpCommand($uuid->toString(), $email, 'password');
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();

        self::assertInstanceOf(UserWasCreated::class, $userCreatedEvent);
    }
}
