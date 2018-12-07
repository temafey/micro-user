<?php

declare(strict_types=1);

namespace Micro\User\Domain\User\ValueObject\Auth;

use Micro\User\Domain\User\ValueObject\Email;

class Credentials
{
    /**
     * @var Email
     */
    public $email;

    /**
     * @var HashedPassword
     */
    public $password;

    public function __construct(Email $email, HashedPassword $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
