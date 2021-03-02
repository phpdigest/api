<?php

declare(strict_types=1);

namespace App\Api\UI\Controller\Admin;

use App\Api\UI\Controller\AbstractController;
use App\Module\User\Domain\Repository\AccountRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;

class UserController extends AbstractController
{
    public const ROUTE_PREFIX = 'admin_panel_user__';
    public const PAGE_ACCOUNT_TABLE = self::ROUTE_PREFIX . 'account_table';

    /**
     * Action for {@see PAGE_ACCOUNT_TABLE}
     */
    public function pageAccountTable(
        ServerRequestInterface $request,
        AccountRepository $urlRepository
    ): ResponseInterface {
        $pageNum = (int)$request->getAttribute('page', 1);

        $dataReader = $urlRepository->findAll();
        $paginator = (new OffsetPaginator($dataReader))
            ->withPageSize(20)
            ->withCurrentPage($pageNum);

        return $this->render(
            'admin/user/accounts',
            [
                'paginator' => $paginator,
            ]
        );
    }
}
