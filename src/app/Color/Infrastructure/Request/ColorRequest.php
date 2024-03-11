<?php

namespace App\Color\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use LVR\Colour\Hex;

class ColorRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'color' => [
                'required',
                'filled',
                'string',
                new Hex(),
                sprintf('unique:colors,color,%s,user_id', $this->userId)
            ],
            'name' => [
                'required',
                'string',
                'filled',
                sprintf('max:%s', Config::COLOR_NAME_LENGTH),
                sprintf('unique:colors,name,%s,user_id', $this->userId)
            ]
        ];
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
