<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Definitions\Reference;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\CsrfViewInjection;

$dbHost = $_ENV['APP_DB_HOST'] ?? getenv('APP_DB_HOST') ?: '127.0.0.1';
$dbPort = $_ENV['APP_DB_PORT'] ?? getenv('APP_DB_PORT') ?: '3306';
$dbName = $_ENV['APP_DB_NAME'] ?? getenv('APP_DB_NAME') ?: 'yii_multivita';
$dbUser = $_ENV['APP_DB_USER'] ?? getenv('APP_DB_USER') ?: 'root';
$dbPass = $_ENV['APP_DB_PASS'] ?? getenv('APP_DB_PASS') ?: '';
$dbCharset = $_ENV['APP_DB_CHARSET'] ?? getenv('APP_DB_CHARSET') ?: 'utf8';

return [
    'application' => require __DIR__ . '/application.php',

    'yiisoft/aliases' => [
        'aliases' => require __DIR__ . '/aliases.php',
    ],

    'yiisoft/view' => [
        'basePath' => null,
        'parameters' => [
            'assetManager' => Reference::to(AssetManager::class),
            'applicationParams' => Reference::to(ApplicationParams::class),
            'aliases' => Reference::to(Aliases::class),
            'urlGenerator' => Reference::to(UrlGeneratorInterface::class),
            'currentRoute' => Reference::to(CurrentRoute::class),
        ],
    ],

    'yiisoft/yii-view-renderer' => [
        'viewPath' => null,
        'layout' => '@src/Web/Shared/Layout/Main/layout.php',
        'injections' => [
            Reference::to(CsrfViewInjection::class),
        ],
    ],

    'yiisoft/db-mysql' => [
        'dsn' => "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$dbCharset}",
        'username' => $dbUser,
        'password' => $dbPass,
    ],

    'yiisoft/db-migration' => [
        'newMigrationNamespace' => 'App\\Migration',
        'sourceNamespaces' => ['App\\Migration'],
    ],
];
