<?php

namespace Micro\User\Domain\User\Repository;

use Micro\User\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface CheckUserByEmailInterface
{
    public function existsEmail(Email $email): ?UuidInterface;
}
