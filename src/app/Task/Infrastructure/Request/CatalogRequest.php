<?php

namespace App\Task\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class CatalogRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'item' => sprintf('required|integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getCatalog(): int
    {
        return $this->item;
    }
}
