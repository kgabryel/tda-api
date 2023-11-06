<?php

namespace App\Video\Application;

use Ds\Set;

interface YtServiceInterface
{
    public function getVideoInfo(string $href): VideoInfo;

    public function getPlaylistItems(string $href): Set;

    public function isPlaylist(string $href): bool;
}
