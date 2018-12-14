<?php

declare(strict_types=1);

namespace Micro\User\Domain;

use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Micro\User\Domain\Event\UserEmailChanged;
use Micro\User\Domain\Event\UserSignedIn;
use Micro\User\Domain\Event\UserWasCreated;
use Micro\User\Domain\Exception\InvalidCredentialsException;
use Micro\User\Domain\ValueObject\Auth\Credentials;
use Micro\User\Domain\ValueObject\Auth\HashedPassword;
use Micro\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

class User extends EventSourcedAggregateRoot
{
    /** @var UuidInterface */
    private $uuid;

    /** @var Email */
    private $email;

    /** @var HashedPassword */
    private $hashedPassword;

    public static function create(UuidInterface $uuid, Credentials $credentials): self
    {
        $user = new self();
        $user->apply(new UserWasCreated($uuid, $credentials));

        return $user;
    }

    public function changeEmail(Email $email): void
    {
        $this->apply(new UserEmailChanged($this->uuid, $email));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        $match = $this->hashedPassword->match($plainPassword);

        if (!$match) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }
        $this->apply(UserSignedIn::create($this->uuid, $this->email));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->uuid;
        $this->setEmail($event->credentials->email);
        $this->setHashedPassword($event->credentials->password);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        Assertion::notEq($this->email->toString(), $event->email->toString(), 'New email should be different');
        $this->setEmail($event->email);
    }

    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

    public function email(): string
    {
        return $this->email->toString();
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }
}
