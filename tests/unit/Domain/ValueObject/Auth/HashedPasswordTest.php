<?php

declare(strict_types=1);

namespace Micro\User\Tests\Unit\Domain\ValueObject\Auth;

use Micro\User\Domain\ValueObject\Auth\HashedPassword;
use PHPUnit\Framework\TestCase;

class HashedPasswordTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     */
    public function encodedPasswordShouldBeValidatedTest(): void
    {
        $pass = HashedPassword::encode('1234567890');
        self::assertTrue($pass->match('1234567890'));
    }

    /**
     * @test
     *
     * @group unit
     */
    public function min6PasswordLengthTest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HashedPassword::encode('12345');
    }

    /**
     * @test
     *
     * @group unit
     */
    public function fromHashPasswordShouldStillValidTest(): void
    {
        $pass = HashedPassword::fromHash((string) HashedPassword::encode('1234567890'));
        self::assertTrue($pass->match('1234567890'));
    }
}
