<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\Auth;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Micro\User\Domain\Auth\AuthenticationProviderInterface;
use Micro\User\Domain\Query\Projections\UserViewInterface;

class AuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->JWTManager = $jwtManager;
    }

    public function generateToken(UserViewInterface $userView): string
    {
        $auth = Auth::fromUser($userView);

        return $this->JWTManager->create($auth);
    }
}
