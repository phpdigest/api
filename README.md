## Installation

1. [Configure project](#configuration)
1. Install dependencies
    ```bash
   composer install --no-dev
    ```
2. Run migrations
    ```bash
    ./vendor/bin/yii migrate/up
    ```


## Requirements

The minimum requirement by this project template that your Web server supports PHP 7.4.0.

## Configuration

Local params example:

```php
<?php // File config/params-local.php

declare(strict_types=1);

return [
    // Common Cycle config
    'yiisoft/yii-cycle' => [
        // Cycle DBAL config
        'dbal' => [
            /**
             * SQL query logger
             * You may use {@see \Yiisoft\Yii\Cycle\Logger\StdoutQueryLogger} class to pass log to
             * stdout or any PSR-compatible logger
             */
            'query-logger' => null,
            // Default database (from 'databases' list)
            'default' => 'default',
            'aliases' => [],
            'databases' => [
                'default' => ['connection' => 'postgres']
            ],
            'connections' => [
                // Example SQLite connection:
                'postgres' => [
                    'driver' => \Spiral\Database\Driver\Postgres\PostgresDriver::class,
                    'options' => [
                        'connection' => 'pgsql:host=127.0.0.1;dbname=YOUR_DB_NAME',
                        'username' => 'YOUR_LOGIN',
                        'password' => 'YOUR_PASSWORD',
                    ],
                ]
            ],
        ],

        /**
         * A list of DB schema providers for {@see \Yiisoft\Yii\Cycle\Schema\SchemaManager}
         * Providers are implementing {@see SchemaProviderInterface}.
         * The configuration is an array of provider class names. Alternatively, you can specify provider class as key
         * and its config as value:
         */
        'schema-providers' => [
            \Yiisoft\Yii\Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => [
                'key' => 'db-schema'
            ],
            // \Yiisoft\Yii\Cycle\Schema\Provider\FromFileSchemaProvider::class => [
            //     'file' => '@runtime/cycle-schema.php'
            // ],
            \Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider::class,
        ],
    ],
];
```


See ["Aliases"](https://github.com/yiisoft/docs/blob/master/guide/en/concept/aliases.md) in the guide.

#### Cache

```php
'yiisoft/cache-file' => [
    'file-cache' => [
        // cache directory path
        'path' => '@runtime/cache'
    ],
],
```

#### Log Target File

```php
use Psr\Log\LogLevel;

'yiisoft/log-target-file' => [
    'file-target' => [
        // route directory file log
        'file' => '@runtime/logs/app.log',
        // levels logs target
        'levels' => [
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ],
    ],
    'file-rotator' => [
        // maximum file size, in kilo-bytes. Defaults to 10240, meaning 10MB.
        'maxfilesize' => 10,
        // number of files used for rotation. Defaults to 5.
        'maxfiles' => 5,
        // the permission to be set for newly created files.
        'filemode' => null,
        // Whether to rotate files by copy and truncate in contrast to rotation by renaming files.
        'rotatebycopy' => null
    ],
],
```

See ["Logging"](https://github.com/yiisoft/docs/blob/master/guide/en/runtime/logging.md) in the guide.

#### Session

```php
'yiisoft/yii-web' => [
    'session' => [
        // options for cookies
        'options' => ['cookie_secure' => 0],
        // session handler
        'handler' => null
    ],
],
```

#### View

```php
'yiisoft/view' => [
    // Custom parameters that are shared among view templates.
    'defaultParameters' => [
        'applicationParameters' => 'App\\Common\\Application\\ApplicationParameters',
        'assetManager' => 'Yiisoft\Assets\AssetManager',
    ],
    'theme' => [
        // Apply pathMap example: ['@resources/layout' => '@resources/theme'] in yiisoft/app
        // Apply pathMap example: ['@resources/layout' => '@modulealiases/theme'] in module
        'pathMap' => [],
        'basePath' => '',
        'baseUrl' => '',
    ],    
],

```

#### Yii Debug

```php
'yiisoft/yii-debug' => [
    // enabled/disabled debugger
    'enabled' => true
],
```

#### Application Layout Parameters

```php
'app' => [
    'charset' => 'UTF-8',
    'language' => 'en',
    'name' => 'My Project',
],
```

## Testing

The template comes with ready to use [Codeception](https://codeception.com/) configuration.
In order to execute tests run:

```
composer run serve > ./runtime/yii.log 2>&1 &
vendor/bin/codecept run
```
