<?php

declare(strict_types=1);

namespace Micro\User\Domain\Event;

use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserSignedIn implements Serializable
{
    /** @var Email */
    public $email;

    /** @var UuidInterface */
    public $uuid;

    public static function create(UuidInterface $uuid, Email $email): self
    {
        $instance = new self();
        $instance->uuid = $uuid;
        $instance->email = $email;

        return $instance;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return self::create(
            Uuid::fromString($data['uuid']),
            Email::fromString($data['email'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid'  => $this->uuid->toString(),
            'email' => $this->email->toString(),
        ];
    }
}
