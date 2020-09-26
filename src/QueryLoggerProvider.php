<?php

declare(strict_types=1);

namespace Chanshige\Laravel;

use Chanshige\Laravel\Foundation\DatabaseQueryLogger;
use Illuminate\Support\ServiceProvider;

class QueryLoggerProvider extends ServiceProvider
{
    public function boot(DatabaseQueryLogger $queryLogger)
    {
        $queryLogger($this->app['config']['logging.query_logger.driver'] ?? null);
    }
}
