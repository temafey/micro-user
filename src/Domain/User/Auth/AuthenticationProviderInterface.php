<?php

declare(strict_types=1);

namespace Micro\User\Domain\User\Auth;

use Micro\User\Domain\User\Query\Projections\UserViewInterface;

interface AuthenticationProviderInterface
{
    public function generateToken(UserViewInterface $userView): string;
}
