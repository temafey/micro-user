<?php

declare(strict_types=1);

namespace Micro\User\Application\Query\User\FindByEmail;

use Micro\User\Application\Query\Item;
use Micro\User\Application\Query\QueryHandlerInterface;
use Micro\User\Domain\Query\Projections\UserViewInterface;
use Micro\User\Domain\Query\Repository\UserReadModelRepositoryInterface;

class FindByEmailHandler implements QueryHandlerInterface
{
    /**
     * @var UserReadModelRepositoryInterface
     */
    private $repository;

    public function __construct(UserReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindByEmailQuery $query): Item
    {
        /** @var UserViewInterface $userView */
        $userView = $this->repository->oneByEmail($query->email);

        return new Item($userView);
    }
}
