<?php

namespace App\Note\Application\Command\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Tworzy nowa notatke
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private string $name;
    private string $content;
    private Hex $textColor;
    private Hex $backgroundText;
    private bool $assignedToDashboard;
    private CatalogsIdsList $catalogsList;
    private TasksIdsList $tasksList;

    public function __construct(
        string $name,
        string $content,
        Hex $textColor,
        Hex $backgroundText,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        TasksIdsList $tasksList
    ) {
        $this->name = $name;
        $this->content = $content;
        $this->textColor = $textColor;
        $this->backgroundText = $backgroundText;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTextColor(): Hex
    {
        return $this->textColor;
    }

    public function getBackgroundText(): Hex
    {
        return $this->backgroundText;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getCatalogsList(): CatalogsIdsList
    {
        return $this->catalogsList;
    }

    public function getTasksList(): TasksIdsList
    {
        return $this->tasksList;
    }
}
