<?php

declare(strict_types=1);

namespace Micro\User\Tests\Domain\User\ValueObject;

use Micro\User\Domain\User\ValueObject\Email;
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
        Email::fromString('asd');
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
        $email = Email::fromString('an@email.com');
        self::assertSame('an@email.com', $email->toString());
        self::assertSame('an@email.com', (string) $email);
    }
}
