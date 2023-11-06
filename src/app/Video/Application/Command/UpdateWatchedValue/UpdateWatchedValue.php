<?php

namespace App\Video\Application\Command\UpdateWatchedValue;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\Command\ModifyVideoCommand;

/**
 * Ustawia film jako obejrzany lub nieobejrzany
 */
#[CommandHandler(UpdateWatchedValueHandler::class)]
class UpdateWatchedValue extends ModifyVideoCommand
{
    private bool $isWatched;

    public function __construct(VideoId $id, bool $isWatched)
    {
        parent::__construct($id);
        $this->isWatched = $isWatched;
    }

    public function isWatched(): bool
    {
        return $this->isWatched;
    }
}
