<?php

declare(strict_types=1);

namespace Micro\User\Application\Query\Event\GetEvents;

use Micro\User\Application\Query\Collection;
use Micro\User\Application\Query\QueryHandlerInterface;
use Micro\User\Domain\Shared\Event\EventRepositoryInterface;

class GetEventsHandler implements QueryHandlerInterface
{
    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @throws \Micro\User\Domain\Shared\Query\Exception\NotFoundException
     */
    public function __invoke(GetEventsQuery $query): Collection
    {
        $result = $this->eventRepository->page($query->page, $query->limit);

        return new Collection($query->page, $query->limit, $result['total'], $result['data']);
    }
}
