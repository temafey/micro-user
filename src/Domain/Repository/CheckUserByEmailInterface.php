<?php

namespace Micro\User\Domain\Repository;

use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface CheckUserByEmailInterface
{
    public function existsEmail(Email $email): ?UuidInterface;
}
