<?php

declare(strict_types=1);

namespace Micro\User\Domain\Factory;

use Micro\User\Domain\Exception\EmailAlreadyExistException;
use Micro\User\Domain\Repository\CheckUserByEmailInterface;
use Micro\User\Domain\User;
use Micro\User\Domain\ValueObject\Auth\Credentials;
use Ramsey\Uuid\UuidInterface;

class UserFactory
{
    /**
     * @var CheckUserByEmailInterface
     */
    private $userCollection;

    public function __construct(CheckUserByEmailInterface $userCollection)
    {
        $this->userCollection = $userCollection;
    }

    public function register(UuidInterface $uuid, Credentials $credentials): User
    {
        if ($this->userCollection->existsEmail($credentials->email)) {
            throw new EmailAlreadyExistException('Email already registered.');
        }

        return User::create($uuid, $credentials);
    }
}
