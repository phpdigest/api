<?php

declare(strict_types=1);

namespace App\Api\UI\Controller\Admin;

use App\Api\UI\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;

class CommonController extends AbstractController
{
    public const ROUTE_PREFIX = 'admin_panel__';
    public const PAGE_INDEX = self::ROUTE_PREFIX . 'index';

    /**
     * Action for {@see P_INDEX}
     */
    public function pageIndex(): ResponseInterface
    {
        return $this->render('admin/index');
    }
}
