<?php

declare(strict_types=1);

use App\Module\Link\Api\UserLinkService;
use App\Module\Link\Domain\Entity\Suggestion;
use App\Module\Link\Domain\Repository\SuggestionRepository;
use App\Module\Link\Service\UserLink;
use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Yiisoft\Factory\Definitions\Reference;

/**  @var array $params */

return [
    UserLinkService::class => Reference::to(UserLink::class),

    # Repositories
    SuggestionRepository::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
                         ->getRepository(Suggestion::class);
    },
];
