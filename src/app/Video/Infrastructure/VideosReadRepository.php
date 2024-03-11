<?php

namespace App\Video\Infrastructure;

use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\ReadRepository;
use App\Video\Application\ViewModel\Video;
use App\Video\Infrastructure\Model\Video as VideoModel;

class VideosReadRepository implements ReadRepository
{
    public function findById(VideoId $videoId, UserId $userId): Video
    {
        return VideoModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('id', '=', $videoId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function find(UserId $userId, VideoId ...$videosIds): array
    {
        return VideoModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->whereIn('id', $videosIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(VideoModel $video) => $video->toViewModel())
            ->toArray();
    }

    public function findAll(UserId $userId): array
    {
        return VideoModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(VideoModel $video) => $video->toViewModel())
            ->toArray();
    }
}
