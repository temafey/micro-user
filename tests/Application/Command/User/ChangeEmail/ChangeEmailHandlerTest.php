<?php

declare(strict_types=1);

namespace Micro\User\Tests\Application\Command\User\ChangeEmail;

use Broadway\Domain\DomainMessage;
use Micro\User\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use Micro\User\Application\Command\User\SignUp\SignUpCommand;
use Micro\User\Domain\User\Event\UserEmailChanged;
use Micro\User\Tests\Application\ApplicationTestCase;
use Micro\User\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Ramsey\Uuid\Uuid;

class ChangeEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function updateUserEmailShouldCommandShouldFireEventTest(): void
    {
        $command = new SignUpCommand($uuid = Uuid::uuid4()->toString(), 'asd@asd.asd', 'password');

        $this
            ->handle($command);
        $email = 'lol@asd.asd';
        $command = new ChangeEmailCommand($uuid, $email);

        $this
            ->handle($command);

        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->service(EventCollectorListener::class);

        /** @var DomainMessage[] $events */
        $events = $eventCollector->popEvents();

        self::assertCount(2, $events);

        /** @var UserEmailChanged $emailChangedEmail */
        $emailChangedEmail = $events[1]->getPayload();

        self::assertInstanceOf(UserEmailChanged::class, $emailChangedEmail);
        self::assertSame($email, $emailChangedEmail->email->toString());
    }
}
