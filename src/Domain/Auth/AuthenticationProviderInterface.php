<?php

declare(strict_types=1);

namespace Micro\User\Domain\Auth;

use Micro\User\Domain\Query\Projections\UserViewInterface;

interface AuthenticationProviderInterface
{
    public function generateToken(UserViewInterface $userView): string;
}
