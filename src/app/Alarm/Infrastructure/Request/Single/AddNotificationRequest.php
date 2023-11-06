<?php

namespace App\Alarm\Infrastructure\Request\Single;

use App\Shared\Infrastructure\NotificationTypesUtils;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Utils\UserUtils;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class AddNotificationRequest extends BasicRequest
{
    /** @var int[] */
    private array $availableTypes;

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
        $this->availableTypes = NotificationTypesUtils::getTypesIdsForUser(UserUtils::getLoggedUser());
    }

    public function rules(): array
    {
        return [
            'notificationTypes' => 'required|array|min:1',
            'notificationTypes.*' => [Rule::in($this->availableTypes)],
            'date' => 'required|date'
        ];
    }

    public function getTime(): Carbon
    {
        return new Carbon($this->date);
    }

    public function getTypes(): array
    {
        return $this->notificationTypes;
    }
}
