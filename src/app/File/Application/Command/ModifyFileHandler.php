<?php

namespace App\File\Application\Command;

use App\File\Application\Query\FindById\FindById;
use App\File\Domain\Entity\File;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\FileId;

abstract class ModifyFileHandler extends CommandHandler
{
    protected function getFile(FileId $fileId): File
    {
        return $this->queryBus->handle(new FindById($fileId, QueryResult::DOMAIN_MODEL));
    }
}
