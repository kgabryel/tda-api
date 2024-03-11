<?php

namespace App\Task\Infrastructure\Model;

use App\Task\Application\ViewModel\TaskStatus as ViewModel;
use App\Task\Domain\Entity\StatusId;
use App\Task\Domain\Entity\TaskStatus as DomainModel;
use App\Task\Domain\TaskStatus as TaskStatusName;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    public $timestamps = false;

    protected $table = 'tasks_statuses';

    protected $fillable = [
        'name',
        'color',
        'icon'
    ];

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getColor(),
            $this->getIcon()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new StatusId($this->getId()),
            TaskStatusName::from($this->getName())
        );
    }

    public function getOrder(): int
    {
        return $this->status_order;
    }

    public function setOrder(int $order): self
    {
        $this->status_order = $order;

        return $this;
    }
}
