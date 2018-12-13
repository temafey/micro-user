<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\Auth;

use Micro\User\Domain\Auth\SessionInterface;
use Micro\User\Domain\Exception\InvalidCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Session implements SessionInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function get(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new InvalidCredentialsException();
        }

        $user = $token->getUser();

        if (!$user instanceof Auth) {
            throw new InvalidCredentialsException();
        }

        return [
            'uuid'     => $user->uuid(),
            'username' => $user->getUsername(),
        ];
    }

    public function sameByUuid(string $uuid): bool
    {
        return $this->get()['uuid']->toString() === $uuid;
    }
}
