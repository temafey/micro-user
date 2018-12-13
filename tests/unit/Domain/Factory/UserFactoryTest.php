<?php

declare(strict_types=1);

namespace Micro\User\Tests\Unit\Domain\Factory;

use Micro\User\Domain\Exception\EmailAlreadyExistException;
use Micro\User\Domain\Factory\UserFactory;
use Micro\User\Domain\Repository\CheckUserByEmailInterface;
use Micro\User\Domain\ValueObject\Auth\Credentials;
use Micro\User\Domain\ValueObject\Auth\HashedPassword;
use Micro\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserFactoryTest extends TestCase implements CheckUserByEmailInterface
{
    /** @var UuidInterface|null */
    private $emailExist;

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function userFactoryShouldCreateUserWhenEmailNotExist()
    {
        $factory = new UserFactory($this);
        $uuid = Uuid::uuid4();
        $email = Email::fromString('as@as.as');
        $user = $factory->register($uuid, new Credentials($email, HashedPassword::encode('password')));

        self::assertSame($user->uuid(), $uuid->toString());
        self::assertSame($user->email(), $email->toString());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function userFactoryMustThrowExceptionIsEmailAlreadyTaken()
    {
        $this->expectException(EmailAlreadyExistException::class);
        $this->emailExist = Uuid::uuid4();
        $factory = new UserFactory($this);
        $uuid = Uuid::uuid4();
        $email = Email::fromString('as@as.as');

        $factory->register($uuid, new Credentials($email, HashedPassword::encode('password')));
    }

    public function existsEmail(Email $email): ?UuidInterface
    {
        return $this->emailExist;
    }
}
