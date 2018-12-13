<?php

namespace Micro\User\Domain\Query\Repository;

use Micro\User\Domain\Query\Projections\UserViewInterface;
use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface UserReadModelRepositoryInterface
{
    public function oneByUuid(UuidInterface $uuid): UserViewInterface;

    public function oneByEmail(Email $email): UserViewInterface;

    public function add(UserViewInterface $userRead): void;

    public function apply(): void;
}
