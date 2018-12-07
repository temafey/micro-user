<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\User\Auth;

use Micro\User\Domain\User\Query\Repository\UserReadModelRepositoryInterface;
use Micro\User\Domain\User\ValueObject\Email;
use Micro\User\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthProvider implements UserProviderInterface
{
    /**
     * @var UserReadModelRepositoryInterface
     */
    private $userReadModelRepository;

    public function __construct(UserReadModelRepositoryInterface $userReadModelRepository)
    {
        $this->userReadModelRepository = $userReadModelRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function loadUserByUsername($email)
    {
        /** @var UserView $user */
        $user = $this->userReadModelRepository->oneByEmail(Email::fromString($email));

        return Auth::fromUser($user);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return Auth::class === $class;
    }
}
