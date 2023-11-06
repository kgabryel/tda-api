<?php

namespace App\Note\Infrastructure;

use App\Core\Cache;
use App\Note\Application\NoteManagerInterface;
use App\Note\Domain\Entity\Note as DomainModel;
use App\Note\Infrastructure\Model\Note;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;

class NoteManager implements NoteManagerInterface
{
    public function create(
        string $name,
        string $content,
        bool $assignedToDashboard,
        Hex $textColor,
        Hex $backgroundColor,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): DomainModel {
        $note = new Note();
        $note->setName($name)
            ->setContent($content)
            ->setAssignedToDashboard($assignedToDashboard)
            ->setTextColor($textColor->getColor())
            ->setBackgroundColor($backgroundColor->getColor())
            ->setUserId($userId)
            ->save();
        $note->catalogs()->attach($catalogsList->getIds());
        $note->tasks()->attach($tasksList->getIds());
        $note->tasksGroups()->attach($tasksGroupsList->getIds());
        $domainModel = $note->toDomainModel();
        $key = self::getCacheKey($domainModel->getNoteId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public static function getCacheKey(NoteId $noteId): string
    {
        return sprintf('notes-%s', $noteId);
    }

    public function update(DomainModel $note): void
    {
        $this->getModel($note->getNoteId())
            ->setAssignedToDashboard($note->isAssignedToDashboard())
            ->setName($note->getName())
            ->setContent($note->getContent())
            ->setTextColor($note->getTextColor()->getColor())
            ->setBackgroundColor($note->getBackgroundColor()->getColor())
            ->update();
    }

    private function getModel(NoteId $noteId): Note
    {
        $note = new Note();
        $note->id = $noteId->getValue();
        $note->exists = true;

        return $note;
    }

    public function delete(NoteId $noteId): void
    {
        $this->getModel($noteId)->delete();
        Cache::forget(self::getCacheKey($noteId));
    }
}
