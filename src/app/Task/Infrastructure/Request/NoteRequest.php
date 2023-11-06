<?php

namespace App\Task\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class NoteRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'item' => sprintf('required|integer|exists:notes,id,user_id,%s', $this->userId)
        ];
    }

    public function getNote(): int
    {
        return $this->item;
    }
}
