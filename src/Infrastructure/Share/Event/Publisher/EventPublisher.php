<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\Share\Event\Publisher;

use Broadway\Domain\DomainMessage;

interface EventPublisher
{
    public function handle(DomainMessage $message): void;

    public function publish(): void;
}
