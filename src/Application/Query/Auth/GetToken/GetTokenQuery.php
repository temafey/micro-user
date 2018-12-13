<?php

declare(strict_types=1);

namespace Micro\User\Application\Query\Auth\GetToken;

use Micro\User\Domain\ValueObject\Email;

class GetTokenQuery
{
    /**
     * @var Email
     */
    public $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
