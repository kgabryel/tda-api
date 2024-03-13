<?php

namespace App\Task\Domain\Service;

use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\UpdateMainTask\StatusChangeDto;
use App\Task\Domain\TaskStatus;
use RuntimeException;

class StatusService
{
    public function statusShouldBeChanged(
        TaskStatus $taskStatus,
        TaskStatus $subtaskStatus,
        TaskId $mainTaskId,
        TaskId $subtaskId
    ): false|StatusChangeDto {
        if ($taskStatus === $subtaskStatus) {
            return false;
        }
        if ($taskStatus === TaskStatus::IN_PROGRESS || $taskStatus === TaskStatus::BLOCKED) {
            return false;
        }
        $subtaskUndone = in_array($subtaskStatus, [TaskStatus::UNDONE, TaskStatus::REJECTED], true);
        if ($taskStatus === TaskStatus::TO_DO && $subtaskUndone) {
            return false;
        }
        if ($taskStatus === TaskStatus::DONE && $subtaskStatus === TaskStatus::REJECTED) {
            return false;
        }
        if ($taskStatus === TaskStatus::TO_DO || $taskStatus === TaskStatus::DONE) {
            return new StatusChangeDto($mainTaskId, TaskStatus::IN_PROGRESS);
        }
        if ($taskStatus === TaskStatus::UNDONE) {
            return new StatusChangeDto($subtaskId, TaskStatus::UNDONE);
        }
        if ($taskStatus === TaskStatus::REJECTED) {
            return new StatusChangeDto($subtaskId, TaskStatus::REJECTED);
        }
        throw new RuntimeException('Unexpected statuses pair.');
    }
}
