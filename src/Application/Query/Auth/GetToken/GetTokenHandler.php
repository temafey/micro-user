<?php

declare(strict_types=1);

namespace Micro\User\Application\Query\Auth\GetToken;

use Micro\User\Application\Query\QueryHandlerInterface;
use Micro\User\Domain\User\Auth\AuthenticationProviderInterface;
use Micro\User\Domain\User\Query\Repository\UserReadModelRepositoryInterface;

class GetTokenHandler implements QueryHandlerInterface
{
    /**
     * @var UserReadModelRepositoryInterface
     */
    private $readModelRepository;

    /**
     * @var AuthenticationProviderInterface
     */
    private $authenticationProvider;

    public function __construct(
        UserReadModelRepositoryInterface $readModelRepository,
        AuthenticationProviderInterface $authenticationProvider
    ) {
        $this->readModelRepository = $readModelRepository;
        $this->authenticationProvider = $authenticationProvider;
    }

    public function __invoke(GetTokenQuery $query)
    {
        $userView = $this->readModelRepository->oneByEmail($query->email);

        return $this->authenticationProvider->generateToken($userView);
    }
}
