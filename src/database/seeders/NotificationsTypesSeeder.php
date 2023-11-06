<?php

namespace Database\Seeders;

use App\Alarm\Application\Query\ExistsNotificationType\ExistsNotificationType;
use App\Alarm\Domain\Entity\NotificationTypeValue;
use App\Alarm\Infrastructure\Model\NotificationsType;
use App\Core\Cqrs\QueryBus;
use Illuminate\Database\Seeder;

class NotificationsTypesSeeder extends Seeder
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus){
        $this->queryBus = $queryBus;
    }
    public function run(): void
    {
        $notifications = [
            'E-mail' => '#ffff00',
            'Web' => '#33cc33',
            'Push' => '#3f51b5'
        ];
        foreach ($notifications as $name => $color) {
            if (!$this->queryBus->handle(new ExistsNotificationType(NotificationTypeValue::from($name)))) {
                NotificationsType::create([
                    'name' => $name,
                    'color' => $color
                ]);
            }
        }
    }
}
