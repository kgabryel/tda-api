<?php

namespace App\Task\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class VideoRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'item' => sprintf('required|integer|exists:videos,id,user_id,%s', $this->userId)
        ];
    }

    public function getVideo(): int
    {
        return $this->item;
    }
}
