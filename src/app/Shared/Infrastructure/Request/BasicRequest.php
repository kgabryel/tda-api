<?php

namespace App\Shared\Infrastructure\Request;

use App\Shared\Infrastructure\Utils\UserUtils;
use Illuminate\Foundation\Http\FormRequest;

abstract class BasicRequest extends FormRequest
{
    protected int $userId;

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->userId = UserUtils::isLogged() ? UserUtils::getLoggedUser()->getId() : 0;
    }

    public function messages(): array
    {
        return [
            '*.*' => 'Invalid data.'
        ];
    }
}
