<?php

declare(strict_types=1);

namespace Micro\User\Tests\Unit\Domain\ValueObject;

use Micro\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalidEmailShouldThrowAnExceptionTest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Email::fromString('test');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function validEmailShouldBeAbleToConvertToStringTest(): void
    {
        $email = Email::fromString('test@email.com');
        self::assertSame('test@email.com', $email->toString());
        self::assertSame('test@email.com', (string) $email);
    }
}
