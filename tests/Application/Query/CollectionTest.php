<?php

declare(strict_types=1);

namespace Micro\User\Tests\Application\Query;

use Micro\User\Application\Query\Collection;
use Micro\User\Domain\Shared\Query\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws NotFoundException
     */
    public function mustThrowNotFoundExceptionOnNotPageFoundTest(): void
    {
        $this->expectException(NotFoundException::class);

        new Collection(2, 10, 2, []);
    }
}
