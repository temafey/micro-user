<?php

declare(strict_types=1);

namespace Micro\User\Application\Command\User\SignIn;

use Micro\User\Application\Command\CommandHandlerInterface;
use Micro\User\Domain\Exception\InvalidCredentialsException;
use Micro\User\Domain\Repository\CheckUserByEmailInterface;
use Micro\User\Domain\Repository\UserRepositoryInterface;
use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

class SignInHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userStore;

    /**
     * @var CheckUserByEmailInterface
     */
    private $userCollection;

    public function __construct(UserRepositoryInterface $userStore, CheckUserByEmailInterface $userCollection)
    {
        $this->userStore = $userStore;
        $this->userCollection = $userCollection;
    }

    public function __invoke(SignInCommand $command): void
    {
        $uuid = $this->uuidFromEmail($command->email);
        $user = $this->userStore->get($uuid);
        $user->signIn($command->plainPassword);
        $this->userStore->store($user);
    }

    private function uuidFromEmail(Email $email): UuidInterface
    {
        $uuid = $this->userCollection->existsEmail($email);

        if (null === $uuid) {
            throw new InvalidCredentialsException();
        }

        return $uuid;
    }
}
