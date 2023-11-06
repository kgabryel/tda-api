<?php

namespace App\Video\Infrastructure;

use Alaouy\Youtube\Facades\Youtube;
use App\Video\Application\VideoInfo;
use App\Video\Application\YtServiceInterface;
use App\Video\Domain\InvalidURLException;
use Ds\Set;

class YtService implements YtServiceInterface
{
    public function getVideoInfo(string $href): VideoInfo
    {
        $info = Youtube::getVideoInfo(Youtube::parseVidFromURL(self::modifyHref($href)));

        return new VideoInfo($info->id, $info->snippet->title);
    }

    private static function modifyHref(string $href): string
    {
        if ($start = stripos($href, 'youtube')) {
            return sprintf('https://www.%s', substr($href, $start));
        }

        return '';
    }

    public function getPlaylistItems(string $href): Set
    {
        $items = Youtube::getPlaylistItemsByPlaylistId(self::getQueryParameter($href, 'list'));
        $videos = new Set();
        foreach ($items['results'] as $info) {
            $videos->add(new VideoInfo($info->contentDetails->videoId, $info->snippet->title));
        }

        return $videos;
    }

    private static function getQueryParameter(string $url, string $parameterName): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidURLException(sprintf('%s is not a valid URL.', $url));
        }
        $parts = parse_url($url);
        if (!isset($parts['query'])) {
            throw new InvalidURLException(sprintf('%s does not contain a %s parameter.', $url, $parameterName));
        }
        parse_str($parts['query'], $parameters);
        if (!isset($parameters[$parameterName])) {
            throw new InvalidURLException(sprintf('%s does not contain a %s parameter.', $url, $parameterName));
        }

        return $parameters[$parameterName];
    }

    public function isPlaylist(string $href): bool
    {
        return str_contains($href, 'playlist');
    }
}
