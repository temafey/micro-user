<?php

declare(strict_types=1);

namespace Micro\User\Application\Command\User\ChangeEmail;

use Micro\User\Application\Command\CommandHandlerInterface;
use Micro\User\Domain\Repository\UserRepositoryInterface;

class ChangeEmailHandler implements CommandHandlerInterface
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->userRepository->get($command->userUuid);
        $user->changeEmail($command->email);
        $this->userRepository->store($user);
    }
}
