<?php

declare(strict_types=1);

namespace Micro\User\Application\Command\User\SignUp;

use Micro\User\Application\Command\CommandHandlerInterface;
use Micro\User\Domain\User\Factory\UserFactory;
use Micro\User\Domain\User\Repository\UserRepositoryInterface;

class SignUpHandler implements CommandHandlerInterface
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserFactory $userFactory, UserRepositoryInterface $userRepository)
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function __invoke(SignUpCommand $command): void
    {
        $user = $this->userFactory->register($command->uuid, $command->credentials);
        $this->userRepository->store($user);
    }
}
