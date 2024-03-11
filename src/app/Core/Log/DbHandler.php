<?php

namespace App\Core\Log;

use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DbHandler extends AbstractProcessingHandler
{
    private string $table;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->table = 'logs';
    }

    protected function write(array $record): void
    {
        $data = [
            'message' => $record['message'],
            'context' => json_encode($record['context']),
            'level' => $record['level'],
            'level_name' => $record['level_name'],
            'channel' => $record['channel'],
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra' => json_encode($record['extra']),
            'formatted' => $record['formatted'],
            'created_at' => date("Y-m-d H:i:s"),
        ];
        DB::connection()->table($this->table)->insert($data);
    }
}
