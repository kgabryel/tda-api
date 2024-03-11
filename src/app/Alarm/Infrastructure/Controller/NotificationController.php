<?php

namespace App\Alarm\Infrastructure\Controller;

use App\Alarm\Application\Command\PeriodicAlarm\DeleteNotification\DeleteNotification as DeletePeriodicAlarmNotification;
use App\Alarm\Application\Command\SingleAlarm\CheckNotification\CheckNotification;
use App\Alarm\Application\Command\SingleAlarm\DeleteNotification\DeleteNotification as DeleteSingleAlarmNotification;
use App\Alarm\Application\Command\SingleAlarm\UncheckNotification\UncheckNotification;
use App\Alarm\Application\DeleteResult;
use App\Alarm\Application\Query\AlarmType;
use App\Alarm\Application\Query\FindByNotificationId\FindByNotificationId;
use App\Alarm\Application\Query\FindByNotificationsGroupId\FindByNotificationsGroupId;
use App\Alarm\Application\Query\FindNotificationTypes\FindNotificationsTypes;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Infrastructure\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends BaseController
{
    public function findAllNotificationTypes(): array
    {
        return $this->queryBus->handle(new FindNotificationsTypes(QueryResult::VIEW_MODEL));
    }

    public function check(int $id): RedirectResponse
    {
        $this->commandBus->handle(new CheckNotification(new NotificationId($id)));
        /** @var SingleAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindByNotificationId(new NotificationId($id)));

        return redirect(
            sprintf(
                '%s?type=%s',
                route('alarms.findById', ['id' => $alarm->getAlarmId()]),
                AlarmType::SINGLE_ALARM->value
            ),
            Response::HTTP_SEE_OTHER
        );
    }

    public function uncheck(int $id): Response|RedirectResponse
    {
        if ($this->commandBus->handleWithResult(new UncheckNotification(new NotificationId($id)))) {
            /** @var SingleAlarm $alarm */
            $alarm = $this->queryBus->handle(new FindByNotificationId(new NotificationId($id)));

            return redirect(
                sprintf(
                    '%s?type=%s',
                    route('alarms.findById', ['id' => $alarm->getAlarmId()]),
                    AlarmType::SINGLE_ALARM->value
                ),
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function delete(int $id, Request $request): Response|RedirectResponse
    {
        $type = AlarmType::from($request->query('type', ''));
        if ($type === AlarmType::SINGLE_ALARM) {
            /** @var SingleAlarm $alarm */
            $alarm = $this->queryBus->handle(new FindByNotificationId(new NotificationId($id)));
            /** @var DeleteResult $result */
            $result = $this->commandBus->handleWithResult(new DeleteSingleAlarmNotification(new NotificationId($id)));
        } else {
            /** @var PeriodicAlarm $alarm */
            $alarm = $this->queryBus->handle(new FindByNotificationsGroupId(new NotificationsGroupId($id)));
            /** @var DeleteResult $result */
            $result = $this->commandBus->handleWithResult(
                new DeletePeriodicAlarmNotification(new NotificationsGroupId($id))
            );
        }

        if ($result === DeleteResult::DELETED) {
            return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
        }

        return redirect(
            sprintf(
                '%s?type=%s',
                route('alarms.findById', ['id' => $alarm->getAlarmId()]),
                $type->value
            ),
            Response::HTTP_SEE_OTHER
        );
    }
}
