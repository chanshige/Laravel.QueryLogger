<?php

declare(strict_types=1);

namespace Chanshige\Laravel;

use Chanshige\Laravel\Foundation\DatabaseQueryLogger;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\ConnectionResolverInterface;

use function ob_get_clean;
use function ob_start;

class DatabaseQueryLoggerTest extends BaseTestCase
{
    /** @var ConnectionResolverInterface $conn */
    private $conn;

    /**
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->conn = $this->app->make(ConnectionResolverInterface::class);
        $this->app->make(DatabaseQueryLogger::class)('output');
    }

    public function testQuery()
    {
        $conn = $this->conn->connection();
        ob_start();
        $conn->beginTransaction();
        $actual = ob_get_clean();
        $this->assertEquals(
            "production.DEBUG: START TRANSACTION {\"connection\":\"test\"} []\n",
            $actual
        );

        ob_start();
        $conn->select(/**@lang text */
            'SELECT DATE(?)',
            ['now']
        );
        $actual = ob_get_clean();
        $this->assertStringContainsString(
            'production.DEBUG: QueryExecuted {"sql":"SELECT DATE(\'now\')","bind":["now"],"time":"0',
            $actual
        );

        ob_start();
        $conn->commit();
        $actual = ob_get_clean();
        $this->assertEquals(
            "production.DEBUG: COMMIT {\"connection\":\"test\"} []\n",
            $actual
        );
    }

    public function testRollback()
    {
        $conn = $this->conn->connection();
        ob_start();
        $conn->beginTransaction();
        $actual = ob_get_clean();
        $this->assertEquals(
            "production.DEBUG: START TRANSACTION {\"connection\":\"test\"} []\n",
            $actual
        );

        ob_start();
        $conn->statement(/**@lang text */ 'CREATE TABLE tests (test varchar(255) NOT NULL)');
        $actual = ob_get_clean();
        $this->assertStringContainsString(
            'production.DEBUG: QueryExecuted {"sql":"CREATE TABLE tests (test varchar(255) NOT NULL)","bind":[],"time":"0.',
            $actual
        );

        ob_start();
        $conn->rollBack();
        $actual = ob_get_clean();
        $this->assertEquals(
            "production.DEBUG: ROLLBACK {\"connection\":\"test\"} []\n",
            $actual
        );
    }
}
