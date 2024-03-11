<?php

namespace App\Video\Application\Command\CreateMany;

use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Video\Application\Command\Create\Create;
use App\Video\Application\VideoInfo;
use App\Video\Application\YtServiceInterface;
use Ds\Set;

class CreateManyHandler extends AssignedUserCommandHandler
{
    private YtServiceInterface $ytService;
    private CommandBus $commandBus;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        YtServiceInterface $ytService,
        CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->ytService = $ytService;
        $this->commandBus = $commandBus;
    }

    public function handle(CreateMany $command): Set
    {
        $ids = new Set();
        $videos = $this->ytService->getPlaylistItems($command->getHref());
        /** @var VideoInfo $video */
        foreach ($videos as $video) {
            $create = new Create(
                $command->isAssignedToDashboard(),
                $video,
                $command->getCatalogsList(),
                $command->getTasksList()
            );
            $ids->add($this->commandBus->handleWithResult($create));
        }

        return $ids;
    }
}
