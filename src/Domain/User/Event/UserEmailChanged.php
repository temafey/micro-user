<?php

declare(strict_types=1);

namespace Micro\User\Domain\User\Event;

use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Micro\User\Domain\User\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserEmailChanged implements Serializable
{
    /**
     * @var UuidInterface
     */
    public $uuid;

    /**
     * @var Email
     */
    public $email;

    public function __construct(UuidInterface $uuid, Email $email)
    {
        $this->email = $email;
        $this->uuid = $uuid;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
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
