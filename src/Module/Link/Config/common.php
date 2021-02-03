<?php

declare(strict_types=1);

use App\Module\Link\Api\LinkSuggestionService;
use App\Module\Link\Domain\Entity\Suggestion;
use App\Module\Link\Domain\Repository\SuggestionRepository;
use App\Module\Link\Service\SuggestionService;
use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Yiisoft\Factory\Definitions\Reference;

/**  @var array $params */

return [
    LinkSuggestionService::class => Reference::to(SuggestionService::class),

    # Repositories
    SuggestionRepository::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
                         ->getRepository(Suggestion::class);
    },
];
