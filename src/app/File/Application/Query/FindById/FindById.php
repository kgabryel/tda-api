<?php

namespace App\File\Application\Query\FindById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\FileId;

/**
 * Pobiera plik na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private FileId $fileId;
    private QueryResult $result;

    public function __construct(FileId $fileId, QueryResult $result)
    {
        $this->fileId = $fileId;
        $this->result = $result;
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }
}
