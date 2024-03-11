<?php

namespace App\Color\Infrastructure\Model;

use App\Color\Application\ViewModel\Color as ColorView;
use App\Color\Domain\Entity\Color as DomainModel;
use App\Color\Domain\Entity\ColorId;
use App\Shared\Domain\Entity\UserId;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'colors';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function toViewModel(): ColorView
    {
        return new ColorView($this->getId(), $this->getName(), $this->getColor());
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
        return new DomainModel(new ColorId($this->getId()));
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
