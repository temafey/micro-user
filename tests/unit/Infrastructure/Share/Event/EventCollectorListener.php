<?php

namespace Micro\User\Tests\Unit\Infrastructure\Share\Event;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;

class EventCollectorListener implements EventListener
{
    private $publishedEvents = [];

    public function handle(DomainMessage $domainMessage): void
    {
        $this->publishedEvents[] = $domainMessage;
    }

    public function popEvents(): array
    {
        $events = $this->publishedEvents;
        $this->publishedEvents = [];

        return $events;
    }
}
