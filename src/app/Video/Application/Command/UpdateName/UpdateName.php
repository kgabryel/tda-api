<?php

namespace App\Video\Application\Command\UpdateName;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\Command\ModifyVideoCommand;

/**
 * Aktualizuje nazwe filmu
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyVideoCommand
{
    private string $name;

    public function __construct(VideoId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
