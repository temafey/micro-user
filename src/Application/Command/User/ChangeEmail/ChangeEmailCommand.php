<?php

declare(strict_types=1);

namespace Micro\User\Application\Command\User\ChangeEmail;

use Micro\User\Domain\User\ValueObject\Email;
use Ramsey\Uuid\Uuid;

class ChangeEmailCommand
{
    /** @var \Ramsey\Uuid\UuidInterface */
    public $userUuid;

    /** @var Email */
    public $email;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $userUuid, string $email)
    {
        $this->userUuid = Uuid::fromString($userUuid);
        $this->email = Email::fromString($email);
    }
}
