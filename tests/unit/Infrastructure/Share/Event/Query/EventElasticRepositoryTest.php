<?php

declare(strict_types=1);

namespace Micro\User\Tests\Unit\Infrastructure\Share\Event\Query;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Micro\User\Domain\Event\UserWasCreated;
use Micro\User\Infrastructure\Share\Event\Query\EventElasticRepository;
use PHPUnit\Framework\TestCase;

class EventElasticRepositoryTest extends TestCase
{
    /**
     * @var EventElasticRepository|null
     */
    private $repo;

    protected function setUp()
    {
        $this->repo = new EventElasticRepository(
            [
                'hosts' => [
                    'elasticsearch',
                ],
            ]
        );
    }

    /**
     * @test
     *
     * @group integration
     *
     * @throws \Assert\AssertionFailedException
     */
    public function anEventShouldBeStoredInElasticTest(): void
    {
        $data = [
            'uuid'        => $uuid = 'e937f793-45d8-41e9-a756-a2bc711e3172',
            'credentials' => [
                'email'    => 'lol@lol.com',
                'password' => 'lkasjbdalsjdbalsdbaljsdhbalsjbhd987',
            ], ];

        $event = new DomainMessage(
            $uuid,
            1,
            new Metadata(),
            UserWasCreated::deserialize($data),
            DateTime::now()
        );

        $this->repo->store($event);
        $this->repo->refresh();

        $result = $this->repo->search([
            'query' => [
                'match' => [
                    'type' => $event->getType(),
                ],
            ],
        ]);

        self::assertSame(1, $result['hits']['total']);
    }

    protected function tearDown()
    {
        $this->repo->delete();
        $this->repo = null;
    }
}
