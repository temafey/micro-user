<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\User\Query;

use Broadway\ReadModel\Projector;
use Micro\User\Domain\User\Event\UserEmailChanged;
use Micro\User\Domain\User\Event\UserWasCreated;
use Micro\User\Domain\User\Query\Repository\UserReadModelRepositoryInterface;
use Micro\User\Infrastructure\User\Query\Projections\UserView;

class UserReadProjectionFactory extends Projector
{
    /**
     * @var UserReadModelRepositoryInterface
     */
    private $repository;

    public function __construct(UserReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $userReadModel = UserView::fromSerializable($userWasCreated);
        $this->repository->add($userReadModel);
    }

    protected function applyUserEmailChanged(UserEmailChanged $emailChanged): void
    {
        /** @var UserView $userReadModel */
        $userReadModel = $this->repository->oneByUuid($emailChanged->uuid);
        $userReadModel->changeEmail($emailChanged->email);
        $this->repository->apply();
    }
}
