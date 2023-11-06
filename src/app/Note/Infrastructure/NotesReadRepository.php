<?php

namespace App\Note\Infrastructure;

use App\Note\Application\ReadRepository;
use App\Note\Application\ViewModel\Note;
use App\Note\Infrastructure\Model\Note as NoteModel;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;

class NotesReadRepository implements ReadRepository
{
    public function findById(NoteId $noteId, UserId $userId): Note
    {
        return NoteModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('id', '=', $noteId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function find(UserId $userId, NoteId ...$notesIds): array
    {
        return NoteModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->whereIn('id', $notesIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(NoteModel $note) => $note->toViewModel())
            ->toArray();
    }

    public function findAll(UserId $userId): array
    {
        return NoteModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(NoteModel $note) => $note->toViewModel())
            ->toArray();
    }
}
