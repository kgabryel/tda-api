<?php

namespace Database\Seeders;

use App\Task\Infrastructure\Model\TaskStatus;
use App\Task\Infrastructure\Repository\TasksStatusesRepository;
use Illuminate\Database\Seeder;
use App\Task\Domain\TaskStatus as TaskStatusName;
class TasksStatusesOrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            TaskStatusName::TO_DO,
            TaskStatusName::IN_PROGRESS,
            TaskStatusName::BLOCKED,
            TaskStatusName::REJECTED,
            TaskStatusName::UNDONE,
            TaskStatusName::DONE
        ];
        foreach ($orders as $index => $name) {
            /** @var TaskStatus $status */
            $status = TaskStatus::where('name', '=', $name)->firstOrFail();
            if ($status->getOrder() !== $index) {
                $status->setOrder($index);
                $status->update();
            }
        }
    }
}
