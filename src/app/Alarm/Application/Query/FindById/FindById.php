<?php

namespace App\Alarm\Application\Query\FindById;

use App\Alarm\Application\Query\AlarmType;
use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Pobiera alarm na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private string $alarmId;
    private QueryResult $result;
    private ?AlarmType $alarmType;

    public function __construct(
        string|AlarmId|AlarmsGroupId $alarmId,
        QueryResult $result,
        ?AlarmType $alarmType = null
    ) {
        if (is_string($alarmId)) {
            $this->alarmId = $alarmId;
            $this->alarmType = $alarmType;
        } else {
            $this->alarmId = $alarmId->getValue();
            $this->alarmType = AlarmType::fromId($alarmId);
        }
        $this->result = $result;
    }

    public function getAlarmId(): string
    {
        return $this->alarmId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }

    public function getAlarmType(): ?AlarmType
    {
        return $this->alarmType;
    }
}
