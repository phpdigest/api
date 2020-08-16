<?php

declare(strict_types=1);

use App\Common\Application\ApplicationParameters;
use Psr\Log\LogLevel;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

return [
    'aliases' => [
        '@root' => dirname(__DIR__),
        '@assets' => '@root/public/assets',
        '@assetsUrl' => '/assets',
        '@npm' => '@root/node_modules',
        '@public' => '@root/public',
        '@resources' => '@root/resources',
        '@runtime' => '@root/runtime',
        '@views' => '@root/resources/views',
        '@message' => '@root/resources/message',
        '@src' => '@root/src',
    ],

    'yiisoft/cache-file' => [
        'file-cache' => [
            'path' => '@runtime/cache'
        ],
    ],

    'yiisoft/form' => [
        'fieldConfig' => [
            'inputCssClass()' => ['form-control input field'],
            'labelOptions()' => [['label' => '']],
            'errorOptions()' => [['class' => 'has-text-left has-text-danger is-italic']],
        ],
    ],

    'yiisoft/i18n' => [
        'locale' => 'en-US',
        'translator' => [
            'path' => '@message/en-US.php'
        ]
    ],

    'yiisoft/log-target-file' => [
        'file-target' => [
            'file' => '@runtime/logs/app.log',
            'levels' => [
                LogLevel::EMERGENCY,
                LogLevel::ERROR,
                LogLevel::WARNING,
                LogLevel::INFO,
                LogLevel::DEBUG,
            ],
        ],
        'file-rotator' => [
            'maxfilesize' => 10,
            'maxfiles' => 5,
            'filemode' => null,
            'rotatebycopy' => null
        ],
    ],

    'yiisoft/mailer' => [
        'mailerInterface' => [
            'composerPath' => '@resources/mail',
            'writeToFiles' => true,
            'writeToFilesPath' => '@runtime/mail',
        ],
        'swiftSmtpTransport' => [
            'host' => 'smtp.example.com',
            'port' => 25,
            'encryption' => null,
            'username' => 'admin@example.com',
            'password' => ''
        ],
    ],

    'yiisoft/view' => [
        'defaultParameters' => [
            'applicationParameters' => ApplicationParameters::class,
            'assetManager' => AssetManager::class,
            'field' => Field::class,
            'url' => UrlGeneratorInterface::class,
            'urlMatcher' => UrlMatcherInterface::class,
        ],
        'theme' => [
            'pathMap' => [],
            'basePath' => '',
            'baseUrl' => '',
        ]
    ],

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
                'default' => ['connection' => 'sqlite']
            ],
            'connections' => [
                // Example SQLite connection:
                'sqlite' => [
                    'driver' => \Spiral\Database\Driver\SQLite\SQLiteDriver::class,
                    'connection' => 'sqlite:@runtime/database.db',
                    'username' => '',
                    'password' => '',
                ]
            ],
        ],

        // Migrations config
        'migrations' => [
            'directory' => '@resources/migrations',
            'namespace' => 'App\\Migration',
            'table' => 'migration',
            'safe' => false,
        ],

        /**
         * {@see \Yiisoft\Yii\Cycle\Factory\OrmFactory} config
         * Either {@see \Cycle\ORM\PromiseFactoryInterface} implementation or null is specified.
         * Docs: @link https://github.com/cycle/docs/blob/master/advanced/promise.md
         */
        'orm-promise-factory' => null,

        /**
         * A list of DB schema providers for {@see \Yiisoft\Yii\Cycle\Schema\SchemaManager}
         * Providers are implementing {@see SchemaProviderInterface}.
         * The configuration is an array of provider class names. Alternatively, you can specify provider class as key
         * and its config as value:
         */
        'schema-providers' => [
            // \Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider::class => [
            //     'generators' => [
            //         // \Cycle\Schema\Generator\SyncTables::class, // sync table changes to database
            //     ]
            // ],
        ],
        /**
         * {@see \Yiisoft\Yii\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor} settings
         * A list of entity directories. You can use {@see \Yiisoft\Aliases\Aliases} in paths.
         */
        'annotated-entity-paths' => [
            '@src/Common/Domain/Entity',
            '@src/Module/Link/Domain/Entity',
        ],
    ],

    'yiisoft/yii-debug' => [
        'enabled' => true
    ],

    'yiisoft/yii-web' => [
        'session' => [
            'options' => ['cookie_secure' => 0],
            'handler' => null
        ],
    ],

    'app' => [
        'charset' => 'UTF-8',
        'language' => 'en',
        'name' => 'My Project'
    ],

    'mailer' => [
        'adminEmail' => 'admin@example.com',
    ],
];
