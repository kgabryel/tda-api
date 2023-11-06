<?php

namespace App\Core\Log;

use Monolog\Logger;

class DbLogger
{
    public function __invoke(array $config)
    {
        return (new Logger('database'))->pushHandler(new DbHandler());
    }
}
