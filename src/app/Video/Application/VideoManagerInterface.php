<?php

namespace App\Video\Application;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Domain\Entity\Video;
use App\Video\Domain\Entity\Video as DomainModel;

interface VideoManagerInterface
{
    public function update(Video $video): void;

    public function delete(VideoId $videoId): void;

    public function create(
        VideoInfo $videoInfo,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): DomainModel;
}
