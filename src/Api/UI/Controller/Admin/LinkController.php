<?php

declare(strict_types=1);

namespace App\Api\UI\Controller\Admin;

use App\Api\UI\Controller\AbstractController;
use App\Module\Link\Domain\Repository\SuggestionRepository;
use App\Module\Link\Domain\Repository\UrlRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\Sort;

class LinkController extends AbstractController
{
    public const ROUTE_PREFIX = 'admin_panel_link__';
    public const PAGE_SUGGESTION_TABLE = self::ROUTE_PREFIX . 'suggestion_table';
    public const PAGE_URL_TABLE = self::ROUTE_PREFIX . 'url_table';

    /**
     * Action for {@see PAGE_SUGGESTION_TABLE}
     */
    public function pageSuggestionTable(
        ServerRequestInterface $request,
        SuggestionRepository $suggestionRepository
    ): ResponseInterface {
        $pageNum = (int)$request->getAttribute('page', 1);

        $dataReader = $suggestionRepository->findAllWithUserAccount()->withSort(Sort::any()->withOrderString('-id'));
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

    /**
     * Action for {@see PAGE_URL_TABLE}
     */
    public function pageUrlTable(
        ServerRequestInterface $request,
        UrlRepository $urlRepository
    ): ResponseInterface {
        $pageNum = (int)$request->getAttribute('page', 1);

        $dataReader = $urlRepository->findAll()->withSort(Sort::any()->withOrderString('-id'));
        $paginator = (new OffsetPaginator($dataReader))
            ->withPageSize(20)
            ->withCurrentPage($pageNum);

        return $this->render(
            'admin/link/urls',
            [
                'paginator' => $paginator,
            ]
        );
    }
}
