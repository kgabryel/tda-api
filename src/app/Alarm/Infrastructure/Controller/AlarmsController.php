<?php

namespace App\Alarm\Infrastructure\Controller;

use App\Alarm\Application\Command\AddCatalog\AddCatalog;
use App\Alarm\Application\Command\PeriodicAlarm\Delete\Delete as DeletePeriodicAlarm;
use App\Alarm\Application\Command\RemoveCatalog\RemoveCatalog;
use App\Alarm\Application\Command\SingleAlarm\Delete\Delete as DeleteSingleAlarm;
use App\Alarm\Application\Query\AlarmType;
use App\Alarm\Application\Query\Find\Find;
use App\Alarm\Application\Query\FindAll\FindAll;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Application\ViewModel\PeriodicAlarm as PeriodicAlarmView;
use App\Alarm\Application\ViewModel\SingleAlarm as SingleAlarmView;
use App\Alarm\Domain\Entity\PeriodicAlarm as PeriodicAlarmEntity;
use App\Alarm\Domain\Entity\SingleAlarm as SingleAlarmEntity;
use App\Alarm\Domain\Exception\AssignedAlarmModified;
use App\Alarm\Infrastructure\Request\CatalogRequest;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlarmsController extends BaseController
{
    public function findById(
        string $id,
        Request $request
    ): SingleAlarmEntity|SingleAlarmView|PeriodicAlarmEntity|PeriodicAlarmView {
        return $this->queryBus->handle(
            new FindById($id, QueryResult::VIEW_MODEL, AlarmType::tryFrom($request->query('type', '')))
        );
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getStringValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...$ids));
    }

    public function delete(string $id, Request $request)
    {
        $alarm = $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));
        if ($alarm instanceof SingleAlarmEntity) {
            $this->commandBus->handle(new DeleteSingleAlarm(new AlarmId($id)));
        } else {
            $this->commandBus->handle(
                new DeletePeriodicAlarm(new AlarmsGroupId($id), $request->query('delete') === 'true')
            );
        }

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function removeCatalog(string $alarmId, int $catalogId): RedirectResponse
    {
        try {
            $this->commandBus->handle(new RemoveCatalog($alarmId, new CatalogId($catalogId)));
        } catch (AssignedAlarmModified) {
            abort(400);
        }
        $alarm = $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));

        return redirect(
            sprintf(
                '%s?type=%s',
                route('alarms.findById', ['id' => $alarmId]),
                $alarm instanceof SingleAlarmEntity ? AlarmType::SINGLE_ALARM->value : AlarmType::PERIODIC_ALARM->value
            ),
            Response::HTTP_SEE_OTHER
        );
    }

    public function addCatalog(string $alarmId, CatalogRequest $request): RedirectResponse
    {
        try {
            $this->commandBus->handle(new AddCatalog($alarmId, new CatalogId($request->getCatalog())));
        } catch (AssignedAlarmModified) {
            abort(400);
        }
        $alarm = $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));

        return redirect(
            sprintf(
                '%s?type=%s',
                route('alarms.findById', ['id' => $alarmId]),
                $alarm instanceof SingleAlarmEntity ? 'single' : 'periodic'
            ),
            Response::HTTP_SEE_OTHER
        );
    }
}
