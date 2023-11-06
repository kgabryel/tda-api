<?php

namespace App\Alarm\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class CatalogRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'catalog' => sprintf('required|integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getCatalog(): int
    {
        return $this->catalog;
    }
}
