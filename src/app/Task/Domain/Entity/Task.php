<?php

namespace App\Task\Domain\Entity;

use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\BookmarksListInterface;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\FilesListInterface;
use App\Shared\Domain\List\NotesListInterface;
use App\Shared\Domain\List\VideosListInterface;

abstract class Task
{
    protected UserId $userId;
    protected string $name;
    protected ?string $content;
    protected CatalogsListInterface $catalogsList;
    protected NotesListInterface $notesList;
    protected BookmarksListInterface $bookmarksList;
    protected FilesListInterface $filesList;
    protected VideosListInterface $videosList;

    protected bool $deleted;

    public function __construct(
        UserId $userId,
        string $name,
        ?string $content,
        CatalogsListInterface $catalogsList,
        NotesListInterface $notesList,
        BookmarksListInterface $bookmarksList,
        FilesListInterface $filesList,
        VideosListInterface $videosList
    ) {
        $this->userId = $userId;
        $this->name = $name;
        $this->content = $content;
        $this->catalogsList = $catalogsList;
        $this->notesList = $notesList;
        $this->bookmarksList = $bookmarksList;
        $this->filesList = $filesList;
        $this->videosList = $videosList;
        $this->deleted = false;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getCatalogsIds(): array
    {
        return $this->catalogsList->getIds();
    }

    public function getBookmarksIds(): array
    {
        return $this->bookmarksList->getIds();
    }

    public function getFilesIds(): array
    {
        return $this->filesList->getIds();
    }

    public function getNotesIds(): array
    {
        return $this->notesList->getIds();
    }

    public function getVideosIds(): array
    {
        return $this->videosList->getIds();
    }

    public function removeCatalog(CatalogId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update catalogs, entity was deleted.');
        }

        return $this->catalogsList->detach($id);
    }

    public function addCatalog(CatalogId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update catalogs, entity was deleted.');
        }

        return $this->catalogsList->attach($id);
    }

    public function removeNote(NoteId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update notes, entity was deleted.');
        }

        return $this->notesList->detach($id);
    }

    public function addNote(NoteId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update notes, entity was deleted.');
        }

        return $this->notesList->attach($id);
    }

    public function removeBookmark(BookmarkId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update bookmarks, entity was deleted.');
        }

        return $this->bookmarksList->detach($id);
    }

    public function addBookmark(BookmarkId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update bookmarks, entity was deleted.');
        }

        return $this->bookmarksList->attach($id);
    }

    public function removeFile(FileId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update files, entity was deleted.');
        }

        return $this->filesList->detach($id);
    }

    public function addFile(FileId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update files, entity was deleted.');
        }

        return $this->filesList->attach($id);
    }

    public function removeVideo(VideoId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update videos, entity was deleted.');
        }

        return $this->videosList->detach($id);
    }

    public function addVideo(VideoId $id): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update videos, entity was deleted.');
        }

        return $this->videosList->attach($id);
    }

    public function updateName(string $name): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "name" value, entity was deleted.');
        }
        if ($this->name === $name) {
            return false;
        }
        $this->name = $name;

        return true;
    }

    public function updateContent(?string $content): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "content" value, entity was deleted.');
        }
        if ($this->content === $content) {
            return false;
        }
        $this->content = $content;

        return true;
    }

    public function delete(): bool
    {
        if ($this->deleted) {
            return false;
        }
        $this->deleted = true;

        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
