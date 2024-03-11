<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;
use App\User\Application\Config\Pagination;
use Illuminate\Validation\Rule;

class ChangeDefaultPaginationRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'value' => ['required', 'integer', Rule::in(Pagination::AVAILABLE_PAGINATION_VALUES)]
        ];
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
