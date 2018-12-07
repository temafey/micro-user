<?php

namespace Micro\User\Domain\User\Query\Projections;

use Broadway\ReadModel\SerializableReadModel;
use Micro\User\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface UserViewInterface extends SerializableReadModel
{
    public function uuid(): UuidInterface;

    public function email(): string;

    public function hashedPassword(): string;

    public function changeEmail(Email $email): void;
}
