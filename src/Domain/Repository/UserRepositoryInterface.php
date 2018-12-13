<?php

declare(strict_types=1);

namespace Micro\User\Domain\Repository;

use Micro\User\Domain\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function get(UuidInterface $uuid): User;

    public function store(User $user): void;
}
