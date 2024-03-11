<?php

namespace App\Core\Notification;

use App\Alarm\Application\Notificator as AlarmsNotificator;
use App\Bookmark\Application\Notificator as BookmarksNotificator;
use App\Catalog\Application\Notificator as CatalogsNotificator;
use App\File\Application\Notificator as FilesNotificator;
use App\Note\Application\Notificator as NotesNotificator;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Task\Application\Notificator as TasksNotificator;
use App\Video\Application\Notificator as VideosNotificator;

class NotificationService implements
    FilesNotificator,
    AlarmsNotificator,
    BookmarksNotificator,
    NotesNotificator,
    TasksNotificator,
    VideosNotificator,
    CatalogsNotificator
{
    public function notesChanges(UserId|int $user, NoteId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::NOTES,
            array_map(static fn(NoteId $noteId) => $noteId->getValue(), $ids)
        );
    }

    private function notify(int $userId, Type $type, array $modifiedData): void
    {
        if ($modifiedData === []) {
            return;
        }
        event(new ModelsModified($userId, $type->value, $modifiedData));
    }

    private function getUserId(UserId|int $user): int
    {
        return is_int($user) ? $user : $user->getValue();
    }

    public function filesChanges(UserId|int $user, FileId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::FILES,
            array_map(static fn(FileId $fileId) => $fileId->getValue(), $ids)
        );
    }

    public function alarmsChanges(UserId|int $user, AlarmsGroupId|AlarmId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::ALARMS,
            array_map(static fn(AlarmsGroupId|AlarmId $alarmId) => $alarmId->getValue(), $ids)
        );
    }

    public function bookmarksChanges(UserId|int $user, BookmarkId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::BOOKMARKS,
            array_map(static fn(BookmarkId $bookmarkId) => $bookmarkId->getValue(), $ids)
        );
    }

    public function tasksChanges(UserId|int $user, TaskId|TasksGroupId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::TASKS,
            array_map(static fn(TaskId|TasksGroupId $taskId) => $taskId->getValue(), $ids)
        );
    }

    public function videosChanges(UserId|int $user, VideoId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::VIDEOS,
            array_map(static fn(VideoId $videoId) => $videoId->getValue(), $ids)
        );
    }

    public function catalogsChanges(UserId|int $user, CatalogId ...$ids): void
    {
        $this->notify(
            $this->getUserId($user),
            Type::CATALOGS,
            array_map(static fn(CatalogId $catalogId) => $catalogId->getValue(), $ids)
        );
    }
}
