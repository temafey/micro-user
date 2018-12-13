<?php

namespace Micro\User\Application\Query\User\FindByEmail;

use Micro\User\Domain\ValueObject\Email;

class FindByEmailQuery
{
    /**
     * @var Email
     */
    public $email;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
