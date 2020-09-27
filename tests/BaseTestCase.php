<?php

declare(strict_types=1);

namespace Chanshige\Laravel;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\LogManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

abstract class BaseTestCase extends TestCase
{
    /** @var Application */
    protected $app;

    /**
     * @throws FileNotFoundException
     */
    protected function setUp(): void
    {
        $this->app = new class () extends Container {
            public function storagePath()
            {
                return BASE_DIR;
            }
        };
        $this->app->singleton('config', static function () {
            return new Repository();
        });
        $this->app->instance(LoggerInterface::class, new LogManager($this->app));
        $this->app->bind(ContainerInterface::class, Container::class);
        (new EventServiceProvider($this->app))->register();
        $this->app->alias('events', EventDispatcher::class);
        $this->config();
        $this->database();

        Container::setInstance($this->app);
    }

    protected function database()
    {
        Model::clearBootedModels();
        $this->app->singleton('db.factory', static function ($app) {
            return new ConnectionFactory($app);
        });
        $this->app->singleton('db', static function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });
        $this->app->bind(
            ConnectionResolverInterface::class,
            DatabaseManager::class
        );

        $this->app->alias('db', DatabaseManager::class);
    }

    /**
     * @throws FileNotFoundException
     */
    protected function config()
    {
        $filesystem = new Filesystem();
        $this->app['config']->set(
            'database',
            $filesystem->getRequire(__DIR__ . '/config/database.php')
        );
        $this->app['config']->set(
            'logging',
            $filesystem->getRequire(BASE_DIR . 'config/logging.php')
        );
        $this->app['files'] = $filesystem;
    }
}
