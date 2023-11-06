<?php

namespace App\Video\Infrastructure;

use App\Core\Cache;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Domain\Entity\Video;
use App\Video\Domain\WriteRepository;
use App\Video\Infrastructure\Model\Video as VideoModel;

class VideosWriteRepository implements WriteRepository
{
    public function findById(VideoId $videoId, UserId $userId): Video
    {
        $vId = $videoId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(sprintf('videos-%s', $vId), static function () use ($vId, $uId) {
            return VideoModel::where('id', '=', $vId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }
}
