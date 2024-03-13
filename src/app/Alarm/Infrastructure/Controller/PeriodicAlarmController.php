<?php

namespace App\Alarm\Infrastructure\Controller;

use App\Alarm\Application\Command\PeriodicAlarm\Activate\Activate;
use App\Alarm\Application\Command\PeriodicAlarm\AddNotification\AddNotification;
use App\Alarm\Application\Command\PeriodicAlarm\AddNotification\NotificationDto;
use App\Alarm\Application\Command\PeriodicAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\PeriodicAlarm\Create\Create;
use App\Alarm\Application\Command\PeriodicAlarm\Create\Notifications;
use App\Alarm\Application\Command\PeriodicAlarm\Deactivate\Deactivate;
use App\Alarm\Application\Command\PeriodicAlarm\UpdateContent\UpdateContent;
use App\Alarm\Application\Command\PeriodicAlarm\UpdateName\UpdateName;
use App\Alarm\Application\Query\AlarmType;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Exception\AssignedAlarmModified;
use App\Alarm\Infrastructure\Request\Periodic\ActivateRequest;
use App\Alarm\Infrastructure\Request\Periodic\AddNotificationRequest;
use App\Alarm\Infrastructure\Request\Periodic\CreateRequest;
use App\Alarm\Infrastructure\Request\Periodic\DeactivateRequest;
use App\Alarm\Infrastructure\Request\Periodic\UpdateRequest;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Infrastructure\BaseController;
use Illuminate\Http\RedirectResponse;

class PeriodicAlarmController extends BaseController
{
    public function create(CreateRequest $request, UuidInterface $uuid): RedirectResponse
    {
        $id = $uuid->getValue();

        $this->commandBus->handle(
            new Create(
                new AlarmDto(
                    new AlarmsGroupId($id),
                    $request->getName(),
                    $request->getText(),
                    new CatalogsIdsList(
                        ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())
                    ),
                    $request->getStart(),
                    $request->getStop(),
                    $request->getInterval(),
                    $request->getIntervalType()
                ),
                new Notifications(new AlarmsGroupId($id), ...$request->getNotifications())
            )
        );

        return $this->redirectToAlarm($id);
    }

    private function redirectToAlarm(string $id): RedirectResponse
    {
        return $this->redirect(
            sprintf(
                '%s?type=%s',
                route('alarms.findById', ['id' => $id]),
                AlarmType::PERIODIC_ALARM->value
            )
        );
    }

    public function update(string $id, UpdateRequest $request): RedirectResponse
    {
        try {
            if ($request->nameFilled()) {
                $this->commandBus->handle(new UpdateName(new AlarmsGroupId($id), $request->getName()));
            }
            if ($request->contentFilled()) {
                $this->commandBus->handle(new UpdateContent(new AlarmsGroupId($id), $request->getText()));
            }
        } catch (AssignedAlarmModified) {
            abort(400);
        }

        return $this->redirectToAlarm($id);
    }

    public function deactivate(string $id, DeactivateRequest $request): RedirectResponse
    {
        /** @var PeriodicAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindById(new AlarmsGroupId($id), QueryResult::DOMAIN_MODEL));
        if ($alarm->hasTask()) {
            abort(400);
        }
        $result = $this->commandBus->handleWithResult(new Deactivate(new AlarmsGroupId($id), $request->getAction()));
        if ($result === false) {
            abort(400);
        }

        return $this->redirectToAlarm($id);
    }

    public function activate(string $id, ActivateRequest $request): RedirectResponse
    {
        /** @var PeriodicAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindById(new AlarmsGroupId($id), QueryResult::DOMAIN_MODEL));
        if ($alarm->hasTask()) {
            abort(400);
        }
        $result = $this->commandBus->handleWithResult(new Activate(new AlarmsGroupId($id), $request->getAction()));
        if ($result === false) {
            abort(400);
        }

        return $this->redirectToAlarm($id);
    }

    public function addNotification(string $id, AddNotificationRequest $request): RedirectResponse
    {
        $this->commandBus->handle(
            new AddNotification(
                new AlarmsGroupId($id),
                new NotificationDto(
                    $request->getTime(),
                    $request->getNotificationTypes(),
                    $request->getHour(),
                    $request->getIntervalBehaviour(),
                    $request->getInterval()
                )
            )
        );

        return $this->redirectToAlarm($id);
    }
}
