<?php

namespace Micro\User\Domain\User\Query\Repository;

use Micro\User\Domain\User\Query\Projections\UserViewInterface;
use Micro\User\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface UserReadModelRepositoryInterface
{
    public function oneByUuid(UuidInterface $uuid): UserViewInterface;

    public function oneByEmail(Email $email): UserViewInterface;

    public function add(UserViewInterface $userRead): void;

    public function apply(): void;
}
