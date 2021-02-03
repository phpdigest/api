<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use Psr\Http\Message\ResponseInterface;

class SiteController extends AbstractController
{
    public const PAGE_INDEX = 'site__index';

    /**
     * Action for {@see PAGE_INDEX}
     */
    public function pageIndex(): ResponseInterface
    {
        return $this->render('site/index');
    }
}
