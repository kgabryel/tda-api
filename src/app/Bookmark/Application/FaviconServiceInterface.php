<?php

namespace App\Bookmark\Application;

interface FaviconServiceInterface
{
    public function getAddress(string $href): ?string;
}
