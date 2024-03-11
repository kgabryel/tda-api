<?php

namespace App\Video\Infrastructure;

use App\Core\Cache;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\VideoInfo;
use App\Video\Application\VideoManagerInterface;
use App\Video\Domain\Entity\Video as DomainModel;
use App\Video\Infrastructure\Model\Video;

class VideoManager implements VideoManagerInterface
{
    public function update(DomainModel $video): void
    {
        $this->getModel($video->getVideoId())
            ->setWatched($video->isWatched())
            ->setAssignedToDashboard($video->isAssignedToDashboard())
            ->setName($video->getName())
            ->update();
    }

    private function getModel(VideoId $videoId): Video
    {
        $video = new Video();
        $video->id = $videoId->getValue();
        $video->exists = true;

        return $video;
    }

    public function delete(VideoId $videoId): void
    {
        $this->getModel($videoId)->delete();
        Cache::forget(self::getCacheKey($videoId));
    }

    public static function getCacheKey(VideoId $videoId): string
    {
        return sprintf('videos-%s', $videoId);
    }

    public function create(
        VideoInfo $videoInfo,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): DomainModel {
        $video = new Video();
        $video->setYtId($videoInfo->getId())
            ->setName($videoInfo->getName())
            ->setWatched(false)
            ->setAssignedToDashboard($assignedToDashboard)
            ->setUserId($userId)
            ->save();
        $video->catalogs()->attach($catalogsList->getIds());
        $video->tasks()->attach($tasksList->getIds());
        $video->tasksGroups()->attach($tasksGroupsList->getIds());
        $domainModel = $video->toDomainModel();
        $key = self::getCacheKey($domainModel->getVideoId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }
}
