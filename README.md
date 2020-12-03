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

## Testing

The template comes with ready to use [Codeception](https://codeception.com/) configuration.
In order to execute tests run:

```
composer run serve > ./runtime/yii.log 2>&1 &
vendor/bin/codecept run
```
