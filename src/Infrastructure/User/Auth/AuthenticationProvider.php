<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\User\Auth;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Micro\User\Domain\User\Auth\AuthenticationProviderInterface;
use Micro\User\Domain\User\Query\Projections\UserViewInterface;

class AuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;

    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }

    public function generateToken(UserViewInterface $userView): string
    {
        $auth = Auth::fromUser($userView);

        return $this->JWTManager->create($auth);
    }
}
