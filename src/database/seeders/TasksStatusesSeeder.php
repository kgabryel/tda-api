<?php

namespace Database\Seeders;

use App\Core\Cqrs\QueryBus;
use App\Task\Application\Query\ExistsTaskStatus\ExistsTaskStatus;
use App\Task\Domain\TaskStatus as TaskStatusName;
use App\Task\Infrastructure\Model\TaskStatus;
use Illuminate\Database\Seeder;

class TasksStatusesSeeder extends Seeder
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function run(): void
    {
        $statuses = [
            [
                'name' => 'to-do',
                'color' => '#FCF032',
                'icon' => 'error_outline'
            ],
            [
                'name' => 'in-progress',
                'color' => '#2C54D5',
                'icon' => 'schedule'
            ],
            [
                'name' => 'done',
                'color' => '#5CB525',
                'icon' => 'check_circle_outline'
            ],
            [
                'name' => 'undone',
                'color' => '#D9421D',
                'icon' => 'highlight_off'
            ],
            [
                'name' => 'blocked',
                'color' => '#800080',
                'icon' => 'lock'
            ],
            [
                'name' => 'rejected',
                'color' => '#D2691E',
                'icon' => 'do_not_disturb_on'
            ]
        ];
        foreach ($statuses as $status) {
            if (!$this->queryBus->handle(new ExistsTaskStatus(TaskStatusName::from($status['name'])))) {
                TaskStatus::create($status);
            }
        }
    }
}
