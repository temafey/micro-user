<?php

declare(strict_types=1);

namespace Micro\User\Application\Query\Auth\GetToken;

use Micro\User\Application\Query\QueryHandlerInterface;
use Micro\User\Domain\Auth\AuthenticationProviderInterface;
use Micro\User\Domain\Query\Repository\UserReadModelRepositoryInterface;

class GetTokenHandler implements QueryHandlerInterface
{
    /**
     * @var UserReadModelRepositoryInterface
     */
    private $readModelRepository;

    /**
     * @var AuthenticationProviderInterface
     */
    private $authProvider;

    public function __construct(
        UserReadModelRepositoryInterface $readModelRepository,
        AuthenticationProviderInterface $authenticationProvider
    ) {
        $this->readModelRepository = $readModelRepository;
        $this->authProvider = $authenticationProvider;
    }

    public function __invoke(GetTokenQuery $query)
    {
        $userView = $this->readModelRepository->oneByEmail($query->email);

        return $this->authProvider->generateToken($userView);
    }
}
