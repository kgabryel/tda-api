<?php

namespace App\Bookmark\Infrastructure;

use App\Bookmark\Application\FaviconServiceInterface;
use App\Video\Domain\InvalidURLException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class FaviconService implements FaviconServiceInterface
{
    public function getAddress(string $href): ?string
    {
        if (filter_var($href, FILTER_VALIDATE_URL) === false) {
            throw new InvalidURLException(sprintf('%s is not a valid URL.', $href));
        }
        $parts = parse_url($href);
        if (isset($parts['fragment'])) {
            $href = str_replace(sprintf('#%s', $parts['fragment']), '', $href);
        }
        if (isset($parts['query'])) {
            $href = str_replace(sprintf('?%s', $parts['query']), '', $href);
        }
        if (isset($parts['path']) && $parts['path'] !== '/') {
            $href = str_replace($parts['path'], '', $href);
        }
        $response = Http::get(sprintf('%s/favicon.ico', $href));
        if ($response->status() === Response::HTTP_OK && $response->body() !== '') {
            return sprintf('%s/favicon.ico', $href);
        }

        return null;
    }
}
