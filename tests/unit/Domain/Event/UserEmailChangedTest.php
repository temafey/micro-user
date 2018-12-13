<?php

declare(strict_types=1);

namespace Micro\User\Tests\Unit\Domain\Event;

use Micro\User\Domain\Event\UserEmailChanged;
use Micro\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class UserEmailChangedTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function eventShouldBeDeserializableTest(): void
    {
        $event = UserEmailChanged::deserialize([
            'uuid'  => 'eb62dfdc-2086-11e8-b467-0ed5f89f718b',
            'email' => 'asd@asd.asd',
        ]);

        self::assertInstanceOf(UserEmailChanged::class, $event);
        self::assertSame('eb62dfdc-2086-11e8-b467-0ed5f89f718b', $event->uuid->toString());
        self::assertInstanceOf(Email::class, $event->email);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function eventShouldFailWhenDeserializeWithWrongDataTest(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        UserEmailChanged::deserialize([
            'uuids'  => 'eb62dfdc-2086-11e8-b467-0ed5f89f718b',
            'emails' => 'asd@asd.asd',
        ]);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function eventShouldBeSerializableTest(): void
    {
        $event = UserEmailChanged::deserialize([
            'uuid'  => 'eb62dfdc-2086-11e8-b467-0ed5f89f718b',
            'email' => 'asd@asd.asd',
        ]);

        $serialized = $event->serialize();

        self::assertArrayHasKey('uuid', $serialized);
        self::assertArrayHasKey('email', $serialized);
    }
}
