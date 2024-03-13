<?php

namespace App\Alarm\Infrastructure\Controller;

use App\Alarm\Application\Command\SingleAlarm\AddNotification\AddNotification;
use App\Alarm\Application\Command\SingleAlarm\AddNotification\NotificationDto;
use App\Alarm\Application\Command\SingleAlarm\CheckWithNotification\CheckWithNotification;
use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\SingleAlarm\Create\Create;
use App\Alarm\Application\Command\SingleAlarm\Create\Notification;
use App\Alarm\Application\Command\SingleAlarm\Create\Notifications;
use App\Alarm\Application\Command\SingleAlarm\Uncheck\Uncheck;
use App\Alarm\Application\Command\SingleAlarm\UpdateContent\UpdateContent;
use App\Alarm\Application\Command\SingleAlarm\UpdateName\UpdateName;
use App\Alarm\Application\Command\SingleAlarm\UpdateTask\UpdateTask;
use App\Alarm\Application\Query\AlarmType;
use App\Alarm\Application\Query\FindByCode\FindByCode;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Exception\AssignedAlarmModified;
use App\Alarm\Infrastructure\Request\Single\AddNotificationRequest;
use App\Alarm\Infrastructure\Request\Single\CreateRequest;
use App\Alarm\Infrastructure\Request\Single\DeactivateRequest;
use App\Alarm\Infrastructure\Request\Single\UpdateRequest;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Infrastructure\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class SingleAlarmController extends BaseController
{
    public function create(CreateRequest $request, UuidInterface $uuid): RedirectResponse
    {
        $id = $uuid->getValue();

        $this->commandBus->handle(
            new Create(
                new AlarmDto(
                    new AlarmId($id),
                    $request->getName(),
                    $request->getText(),
                    new CatalogsIdsList(
                        ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())
                    )
                ),
                new Notifications(new AlarmId($id), ...$request->getNotifications())
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
                AlarmType::SINGLE_ALARM->value
            )
        );
    }

    public function update(string $id, UpdateRequest $request): RedirectResponse
    {
        try {
            if ($request->nameFilled()) {
                $this->commandBus->handle(new UpdateName(new AlarmId($id), $request->getName()));
            }
            if ($request->contentFilled()) {
                $this->commandBus->handle(new UpdateContent(new AlarmId($id), $request->getText()));
            }
            if ($request->taskFilled()) {
                $task = $request->getTask();
                $this->commandBus->handle(new UpdateTask(new AlarmId($id), $task === null ? null : new TaskId($task)));
            }
        } catch (AssignedAlarmModified) {
            abort(400);
        }

        return $this->redirectToAlarm($id);
    }

    public function deactivate(DeactivateRequest $request): RedirectResponse|Response
    {
        /** @var SingleAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindByCode($request->getCode()));
        if ($this->commandBus->handleWithResult(new CheckWithNotification($alarm->getAlarmId()))) {
            return $this->redirectToAlarm($alarm->getAlarmId()->getValue());
        }

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function check(string $id): RedirectResponse|Response
    {
        if ($this->commandBus->handleWithResult(new CheckWithNotification(new AlarmId($id)))) {
            return $this->redirectToAlarm($id);
        }

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function addNotification(string $id, AddNotificationRequest $request): RedirectResponse
    {
        $this->commandBus->handle(
            new AddNotification(
                new NotificationDto(
                    new AlarmId($id),
                    new Notification(
                        $request->getTime()->toDateTimeImmutable(),
                        new NotificationTypesList(
                            ...array_map(static fn(int $id) => new NotificationTypeId($id), $request->getTypes())
                        )
                    )
                )
            )
        );

        return $this->redirectToAlarm($id);
    }

    public function uncheck(string $id): RedirectResponse|Response
    {
        if ($this->commandBus->handleWithResult(new Uncheck(new AlarmId($id)))) {
            return $this->redirectToAlarm($id);
        }

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
