<?php

namespace App\Note\Infrastructure;

use App\Core\Cache;
use App\Note\Domain\Entity\Note;
use App\Note\Domain\WriteRepository;
use App\Note\Infrastructure\Model\Note as NoteModel;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;

class NotesWriteRepository implements WriteRepository
{
    public function findById(NoteId $noteId, UserId $userId): Note
    {
        $nId = $noteId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(NoteManager::getCacheKey($noteId), static function() use ($nId, $uId) {
            return NoteModel::where('id', '=', $nId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }
}
