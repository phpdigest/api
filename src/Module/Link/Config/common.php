<?php

declare(strict_types=1);

use App\Module\Link\Api\UserLinkService;
use App\Module\Link\Domain\Entity\Link;
use App\Module\Link\Domain\Repository\LinkRepository;
use App\Module\Link\Service\UserLink;
use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Yiisoft\Factory\Definitions\Reference;

/**  @var array $params */

return [
    UserLinkService::class => Reference::to(UserLink::class),

    # Repositories
    LinkRepository::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
                         ->getRepository(Link::class);
    },
];
