<?php

namespace App\User\Application\Command\AddPushSubscription;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Service\WebPushServiceInterface;
use App\User\Application\Query\GetLoggedUser\GetLoggedUser;
use App\User\Application\UserManagerInterface;
use App\User\Domain\Entity\User;

class AddPushSubscriptionHandler extends AssignedUserCommandHandler
{
    private UserManagerInterface $userManager;
    private WebPushServiceInterface $webPushService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        UserManagerInterface $userManager,
        WebPushServiceInterface $webPushService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->userManager = $userManager;
        $this->webPushService = $webPushService;
    }

    public function handle(AddPushSubscription $command): void
    {
        $added = $this->userManager->addSubscription(
            $this->userId,
            $command->getEndpoint(),
            $command->getAuth(),
            $command->getP256dh()
        );

        if ($added) {
            /** @var User $user */
            $user = $this->queryBus->handle(new GetLoggedUser());
            $this->webPushService->addWelcomeNotification(
                $command->getEndpoint(),
                $command->getAuth(),
                $command->getP256dh(),
                $user->getNotificationLanguage()->value
            )->send();
        }
    }
}
