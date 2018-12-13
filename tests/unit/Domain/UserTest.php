<?php

namespace Micro\User\Tests\Unit\Domain;

use Broadway\Domain\DomainMessage;
use Micro\User\Domain\Event\UserEmailChanged;
use Micro\User\Domain\Event\UserWasCreated;
use Micro\User\Domain\User;
use Micro\User\Domain\ValueObject\Auth\Credentials;
use Micro\User\Domain\ValueObject\Auth\HashedPassword;
use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function givenValidEmailItShouldCreateAUserInstanceTest(): void
    {
        $emailString = 'test@test.com';
        $user = User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailString),
                HashedPassword::encode('password')
            )
        );

        self::assertSame($emailString, $user->email());
        self::assertNotNull($user->uuid());

        $events = $user->getUncommittedEvents();

        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');

        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();

        self::assertInstanceOf(UserWasCreated::class, $event->getPayload(), 'First event should be UserWasCreated');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function givenNewEmailItShouldChangeIfNotEqToPrevEmailTest(): void
    {
        $emailString = 'test@test.com';
        $user = User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailString),
                HashedPassword::encode('password')
            )
        );

        $newEmail = 'test@gmail.com';
        $user->changeEmail(Email::fromString($newEmail));
        self::assertSame($user->email(), $newEmail, 'Emails should be equals');

        $events = $user->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), '2 event should be in the buffer');

        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet('1');
        self::assertInstanceOf(
            UserEmailChanged::class,
            $event->getPayload(),
            'Second event should be UserEmailChanged'
        );
    }
}
