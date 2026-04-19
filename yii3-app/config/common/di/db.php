<?php

declare(strict_types=1);

use Psr\SimpleCache\CacheInterface;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Mysql\Connection;
use Yiisoft\Db\Mysql\Driver;

/** @var array $params */

return [
    ConnectionInterface::class => static fn (CacheInterface $cache) => new Connection(
        new Driver(
            $params['yiisoft/db-mysql']['dsn'],
            $params['yiisoft/db-mysql']['username'],
            $params['yiisoft/db-mysql']['password'],
        ),
        new SchemaCache($cache),
    ),
    Connection::class => static fn (ConnectionInterface $connection) => $connection,
];
