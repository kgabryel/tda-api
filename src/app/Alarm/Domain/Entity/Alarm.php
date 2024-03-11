<?php

namespace App\Alarm\Domain\Entity;

use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;

abstract class Alarm
{
    protected UserId $userId;
    protected string $name;
    protected ?string $content;
    protected CatalogsListInterface $catalogsList;
    protected bool $deleted;

    public function __construct(UserId $userId, string $name, ?string $content, CatalogsListInterface $catalogsList)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->content = $content;
        $this->catalogsList = $catalogsList;
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
