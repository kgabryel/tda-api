<?php

namespace App\Color\Application\EventHandler;

use App\Color\Application\ColorManagerInterface;
use App\Color\Domain\Event\Deleted;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Deleted::class)]
class DeletedHandler
{
    private ColorManagerInterface $colorManager;

    public function __construct(ColorManagerInterface $colorManager)
    {
        $this->colorManager = $colorManager;
    }

    public function handle(Deleted $event): void
    {
        $this->colorManager->delete($event->getColorId());
    }
}
