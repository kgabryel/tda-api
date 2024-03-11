<?php

namespace App\Task\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class FileRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'item' => sprintf('required|integer|exists:files,id,user_id,%s', $this->userId)
        ];
    }

    public function getFile(): int
    {
        return $this->item;
    }
}
