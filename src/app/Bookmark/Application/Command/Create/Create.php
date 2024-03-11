<?php

namespace App\Bookmark\Application\Command\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Tworzy nowa zakladke
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private string $name;
    private string $href;
    private Hex $textColor;
    private Hex $backgroundColor;
    private bool $assignedToDashboard;
    private CatalogsIdsList $catalogsList;
    private TasksIdsList $tasksList;

    public function __construct(
        string $name,
        string $href,
        Hex $textColor,
        Hex $backgroundColor,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        TasksIdsList $tasksList
    ) {
        $this->name = $name;
        $this->href = $href;
        $this->textColor = $textColor;
        $this->backgroundColor = $backgroundColor;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getTextColor(): Hex
    {
        return $this->textColor;
    }

    public function getBackgroundColor(): Hex
    {
        return $this->backgroundColor;
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
