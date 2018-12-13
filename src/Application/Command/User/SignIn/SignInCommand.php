<?php

declare(strict_types=1);

namespace Micro\User\Application\Command\User\SignIn;

use Micro\User\Domain\ValueObject\Email;

class SignInCommand
{
    /**
     * @var Email
     */
    public $email;

    /**
     * @var string
     */
    public $plainPassword;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}
