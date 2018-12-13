<?php

declare(strict_types=1);

namespace Micro\User\Domain\Event;

use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Micro\User\Domain\ValueObject\Auth\Credentials;
use Micro\User\Domain\ValueObject\Auth\HashedPassword;
use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserWasCreated implements Serializable
{
    /**
     * @var UuidInterface
     */
    public $uuid;

    /**
     * @var Credentials
     */
    public $credentials;

    public function __construct(UuidInterface $uuid, Credentials $credentials)
    {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'credentials');

        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'])
            )
        );
    }

    public function serialize(): array
    {
        return [
            'uuid'        => $this->uuid->toString(),
            'credentials' => [
                'email'    => $this->credentials->email->toString(),
                'password' => $this->credentials->password->toString(),
            ],
        ];
    }
}
