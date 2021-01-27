<?php

declare(strict_types=1);

namespace App\Api\UI\Controller\Admin;

use App\Api\UI\Controller\AbstractController;
use App\Module\Link\Domain\Repository\SuggestionRepository;
use App\Module\User\Domain\Repository\AccountRepository;
use App\Module\User\Domain\Repository\IdentityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\User\User;

class LinkController extends AbstractController
{
    public const ROUTE_PREFIX = 'admin_panel_link__';
    public const PAGE_SUGGESTION_TABLE = self::ROUTE_PREFIX . 'table';

    /**
     * Action for {@see P_SUGGESTION_TABLE}
     */
    public function pageSuggestionTable(
        ServerRequestInterface $request,
        SuggestionRepository $suggestionRepository
    ): ResponseInterface {
        $pageNum = (int)$request->getAttribute('page', 1);

        $dataReader = $suggestionRepository->findAllWithUserAccount()->withSort((new Sort(['id']))->withOrderString('-id'));
        $paginator = (new OffsetPaginator($dataReader))
            ->withPageSize(20)
            ->withCurrentPage($pageNum);

        return $this->render(
            'admin/link/suggestions',
            [
                'paginator' => $paginator,
            ]
        );
    }
}
