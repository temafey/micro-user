<?php

declare(strict_types=1);

namespace Micro\User\Domain\User\Repository;

use Micro\User\Domain\User\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function get(UuidInterface $uuid): User;

    public function store(User $user): void;
}
