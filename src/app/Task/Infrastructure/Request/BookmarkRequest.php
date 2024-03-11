<?php

namespace App\Task\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class BookmarkRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'item' => sprintf('required|integer|exists:bookmarks,id,user_id,%s', $this->userId)
        ];
    }

    public function getBookmark(): int
    {
        return $this->item;
    }
}
