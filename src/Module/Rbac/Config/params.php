<?php

declare(strict_types=1);

use App\Module\Rbac\Command\Role\AssignCommand;

return [
    'yiisoft/yii-console' => [
        'commands' => [
            AssignCommand::$defaultName => AssignCommand::class,
        ],
    ],
];
