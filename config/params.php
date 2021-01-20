<?php

declare(strict_types=1);

use App\Api\Telegram\Command\SetWebhookCommand;
use App\Common\Application\ApplicationParameters;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

return [
    'app' => [
        'charset' => 'UTF-8',
        'language' => 'en',
        'name' => 'PHP Digest service',
    ],

    'yiisoft/aliases' => [
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
    ],

    'yiisoft/i18n' => [
        'translator' => [
            'locale' => 'en-US',
            'fallbackLocale' => 'en-US'
        ]
    ],

    'yiisoft/mailer' => [
        'composer' => [
            'composerView' => '@resources/mail'
        ],
        'fileMailer' => [
            'fileMailerStorage' => '@runtime/mail'
        ],
        'writeToFiles' => true
    ],

    'swiftmailer/swiftmailer' => [
        'SwiftSmtpTransport' => [
            'host' => 'smtp.example.com',
            'port' => 25,
            'encryption' => null,
            'username' => 'admin@example.com',
            'password' => ''
        ]
    ],

    'yiisoft/view' => [
        'basePath' => '@views',
        'defaultParameters' => [
            'applicationParameters' => Reference::to(ApplicationParameters::class),
            'assetManager' => Reference::to(AssetManager::class),
            'field' => Reference::to(Field::class),
            'url' => Reference::to(UrlGeneratorInterface::class),
            'urlMatcher' => Reference::to(UrlMatcherInterface::class)
        ]
    ],

    'yiisoft/yii-view' => [
        'viewBasePath' => '@views',
        'layout' => '@resources/layout/main',
        'injections' => [
            Reference::to(\App\Api\UI\Injection\LayoutViewInjection::class),
            Reference::to(\Yiisoft\Yii\View\CsrfViewInjection::class),
        ],
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
            '@src/Module/*/Domain/Entity',
        ],
    ],

    'yiisoft/yii-debug' => [
        'enabled' => true
    ],

    'mailer' => [
        'adminEmail' => 'admin@example.com'
    ],

    'telegram-bot' => [
        'token' => '',
        'chat-config' => [
            'clean-mode' => false,
        ],
    ],

    'yiisoft/yii-console' => [
        'autoExit' => false,
        'commands' => [
            SetWebhookCommand::$defaultName => SetWebhookCommand::class,
        ],
    ],

    'yiisoft/router' => [
        'enableCache' => false,
    ],
];
