<?php

declare(strict_types=1);

use App\Api\UI\Controller\Admin\LinkController;
use App\Api\UI\Widget\OffsetPagination;
use App\Api\UI\Widget\SourceIcon;
use App\Module\Link\Domain\Entity\Url;
use Yiisoft\Html\Html;

/**
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var Yiisoft\Form\Widget\Field $field
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var \Yiisoft\Data\Paginator\OffsetPaginator<mixed, Url> $paginator
 */

$this->params['breadcrumbs'] = 'Url list';

$this->setTitle('Url list');

?>
<h3>Total URLs: <?= $paginator->getTotalItems() ?></h3>
<table class="table table-hover table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>URL</th>
            <th class="text-end">Created at</th>
        </tr>
    </thead>
    <tbody>
    <?php
        /** @var Url $link */
        foreach ($paginator->read() as $link) {
            ?>
            <tr>
                <td class="text-nowrap fw-bold"><?= $link->id ?></td>
                <td>
                    <code><?= Html::encode($link->__toString()) ?></code>
                    <?= Html::a(
                        '<i class="fa fa-share"></i>',
                        $link->__toString(),
                        ['rel' => 'nofollow noreferrer', 'target' => '_blank']
                    ) ?>
                </td>
                <td class="text-nowrap text-muted text-end"><?= $link->created_at->format('d-m-Y') ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?= OffsetPagination::widget()
    ->paginator($paginator)
    ->urlGenerator(static fn($page) => $url->generate(LinkController::PAGE_SUGGESTION_TABLE, ['page' => $page]))
    ->__toString();
