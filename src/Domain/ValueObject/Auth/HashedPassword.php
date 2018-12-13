<?php

declare(strict_types=1);

namespace Micro\User\Domain\ValueObject\Auth;

use Assert\Assertion;

final class HashedPassword
{
    /** @var string */
    private $hashedPassword;

    public const COST = 12;

    private function __construct()
    {
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function encode(string $plainPassword): self
    {
        $pass = new self();
        $pass->hash($plainPassword);

        return $pass;
    }

    public static function fromHash(string $hashedPassword): self
    {
        $pass = new self();
        $pass->hashedPassword = $hashedPassword;

        return $pass;
    }

    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function hash(string $plainPassword): void
    {
        $this->validate($plainPassword);
        $this->hashedPassword = (string) password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => self::COST]);
    }

    public function toString(): string
    {
        return $this->hashedPassword;
    }

    public function __toString(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    private function validate(string $raw): void
    {
        Assertion::minLength($raw, 6, 'Min 6 characters password');
    }
}
