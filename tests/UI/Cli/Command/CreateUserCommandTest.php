<?php

declare(strict_types=1);

namespace Micro\User\Tests\UI\Cli\Command;

use League\Tactician\CommandBus;
use Micro\User\Application\Query\Item;
use Micro\User\Application\Query\User\FindByEmail\FindByEmailQuery;
use Micro\User\Infrastructure\User\Query\Projections\UserView;
use Micro\User\Tests\UI\Cli\AbstractConsoleTestCase;
use Micro\User\UI\Cli\Command\CreateUserCommand;
use Ramsey\Uuid\Uuid;

class CreateUserCommandTest extends AbstractConsoleTestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function commandIntegrationWithBusSuccessTest(): void
    {
        $email = 'test@gmail.com';
        /** @var CommandBus $commandBus */
        $commandBus = $this->service('tactician.commandbus.command');
        $commandTester = $this->app($command = new CreateUserCommand($commandBus), 'app:create-user');

        $commandTester->execute([
            'command'  => $command->getName(),
            'uuid'     => Uuid::uuid4()->toString(),
            'email'    => $email,
            'password' => 'testpass',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('User Created:', $output);
        $this->assertContains('Email: test@gmail.com', $output);

        /** @var Item $item */
        $item = $this->ask(new FindByEmailQuery($email));
        /** @var UserView $userRead */
        $userRead = $item->readModel;

        self::assertInstanceOf(Item::class, $item);
        self::assertInstanceOf(UserView::class, $userRead);
        self::assertSame($email, $userRead->email());
    }
}
