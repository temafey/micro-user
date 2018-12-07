<?php

declare(strict_types=1);

namespace Micro\User\Tests\Application\Query\User\FindByEmail;

use Micro\User\Application\Command\User\SignUp\SignUpCommand;
use Micro\User\Application\Query\Item;
use Micro\User\Application\Query\User\FindByEmail\FindByEmailQuery;
use Micro\User\Infrastructure\User\Query\Projections\UserView;
use Micro\User\Tests\Application\ApplicationTestCase;
use Ramsey\Uuid\Uuid;

class FindByEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function queryCommandIntegrationTest(): void
    {
        $email = $this->createUserRead();

        $this->fireTerminateEvent();

        /** @var Item $item */
        $item = $this->ask(new FindByEmailQuery($email));
        /** @var UserView $userRead */
        $userRead = $item->readModel;

        self::assertInstanceOf(Item::class, $item);
        self::assertInstanceOf(UserView::class, $userRead);
        self::assertSame($email, $userRead->email());
    }

    /**
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    private function createUserRead(): string
    {
        $uuid = Uuid::uuid4()->toString();
        $email = 'lol@lol.com';

        $this->handle(new SignUpCommand($uuid, $email, 'password'));

        return $email;
    }
}
