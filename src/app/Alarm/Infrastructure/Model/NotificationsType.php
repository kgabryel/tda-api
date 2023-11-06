<?php

namespace App\Alarm\Infrastructure\Model;

use App\Alarm\Application\ViewModel\NotificationsType as ViewModel;
use App\Alarm\Domain\Entity\NotificationsType as DomainModel;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypeValue;
use Illuminate\Database\Eloquent\Model;

class NotificationsType extends Model
{
    public $timestamps = false;

    protected $table = 'available_notifications_types';

    protected $fillable = [
        'name',
        'color'
    ];

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getColor()
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

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new NotificationTypeId($this->getId()),
            NotificationTypeValue::from($this->getName())
        );
    }
}
