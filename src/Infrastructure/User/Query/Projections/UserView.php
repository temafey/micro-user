<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\User\Query\Projections;

use Broadway\Serializer\Serializable;
use Micro\User\Domain\User\Query\Projections\UserViewInterface;
use Micro\User\Domain\User\ValueObject\Auth\Credentials;
use Micro\User\Domain\User\ValueObject\Auth\HashedPassword;
use Micro\User\Domain\User\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserView implements UserViewInterface
{
    /** @var UuidInterface */
    private $uuid;

    /** @var Credentials */
    private $credentials;

    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();

        $instance->uuid = Uuid::fromString($data['uuid']);
        $instance->credentials = new Credentials(
            Email::fromString($data['credentials']['email']),
            HashedPassword::fromHash($data['credentials']['password'] ?? '')
        );

        return $instance;
    }

    public function serialize(): array
    {
        return [
            'uuid'        => $this->getId(),
            'credentials' => [
                'email' => (string) $this->credentials->email,
            ],
        ];
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): string
    {
        return (string) $this->credentials->email;
    }

    public function changeEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }

    public function hashedPassword(): string
    {
        return (string) $this->credentials->password;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
