<?php

declare(strict_types=1);

namespace Chanshige\Laravel\Foundation;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;

use function number_format;
use function str_replace;
use function vsprintf;

class DatabaseQueryLogger
{
    /** @var EventDispatcher $event */
    private $event;

    /** @var LoggerInterface $logger */
    private $logger;

    public function __construct(
        EventDispatcher $event,
        LoggerInterface $logger
    ) {
        $this->event = $event;
        $this->logger = $logger;
    }

    public function __invoke(?string $driver = null)
    {
        if ($driver !== null && $this->logger instanceof LogManager) {
            $this->logger = $this->logger->driver($driver);
        }

        $this->event->listen(QueryExecuted::class, function ($query) {
            $this->logger->debug('QueryExecuted', [
                'sql' => $this->toSql($query->sql, $query->bindings),
                'bind' => $query->bindings,
                'time' => number_format($query->time / 1000, 15),
            ]);
        });
        $this->event->listen(TransactionBeginning::class, function ($event) {
            $this->logger->debug('START TRANSACTION', ['connection' => $event->connectionName]);
        });
        $this->event->listen(TransactionCommitted::class, function ($event) {
            $this->logger->debug('COMMIT', ['connection' => $event->connectionName]);
        });
        $this->event->listen(TransactionRolledBack::class, function ($event) {
            $this->logger->debug('ROLLBACK', ['connection' => $event->connectionName]);
        });
    }

    /**
     * @param array<int, mixed> $bindings
     */
    private function toSql(string $query, array $bindings): string
    {
        return vsprintf(str_replace('?', "'%s'", $query), $bindings);
    }
}
