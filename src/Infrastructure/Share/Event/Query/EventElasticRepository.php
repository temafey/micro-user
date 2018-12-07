<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\Share\Event\Query;

use Broadway\Domain\DomainMessage;
use Micro\User\Domain\Shared\Event\EventRepositoryInterface;
use Micro\User\Infrastructure\Share\Query\Repository\ElasticRepository;

final class EventElasticRepository extends ElasticRepository implements EventRepositoryInterface
{
    private const INDEX = 'events';

    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }

    public function store(DomainMessage $message): void
    {
        $document = [
            'type'        => $message->getType(),
            'payload'     => $message->getPayload()->serialize(),
            'occurred_on' => $message->getRecordedOn()->toString(),
        ];
        $this->add($document);
    }
}
