<?php

declare(strict_types=1);

namespace Micro\User\Tests\Application\Command\User\SignIn;

use Broadway\Domain\DomainMessage;
use Micro\User\Application\Command\User\SignIn\SignInCommand;
use Micro\User\Application\Command\User\SignUp\SignUpCommand;
use Micro\User\Domain\User\Event\UserSignedIn;
use Micro\User\Domain\User\Exception\InvalidCredentialsException;
use Micro\User\Tests\Application\ApplicationTestCase;
use Micro\User\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Ramsey\Uuid\Uuid;

final class SignInTest extends ApplicationTestCase
{
    /**
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    protected function setUp()
    {
        parent::setUp();

        $command = new SignUpCommand(
            Uuid::uuid4()->toString(),
            'asd@asd.asd',
            'qwerqwer'
        );

        $this->handle($command);
    }

    /**
     * @test
     *
     * @group integration
     *
     * @throws \Assert\AssertionFailedException
     */
    public function userSignUpWithValidCredentialsTest(): void
    {
        $command = new SignInCommand(
            'asd@asd.asd',
            'qwerqwer'
        );
        $this->handle($command);
        /** @var EventCollectorListener $eventCollector */
        $eventCollector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $eventCollector->popEvents();

        self::assertInstanceOf(UserSignedIn::class, $events[1]->getPayload());
    }

    /**
     * @test
     *
     * @group integration
     *
     * @dataProvider invalidCredentials
     *
     * @throws \Assert\AssertionFailedException
     */
    public function userSignUpWithInvalidCredentialsMustThrowDomainException(string $email, string $pass): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $command = new SignInCommand($email, $pass);
        $this->handle($command);
    }

    public function invalidCredentials(): array
    {
        return [
          [
              'email' => 'asd@asd.asd',
              'pass'  => 'qwerqwer123',
          ],
          [
              'email' => 'asd@asd.com',
              'pass'  => 'qwerqwer',
          ],
        ];
    }
}
