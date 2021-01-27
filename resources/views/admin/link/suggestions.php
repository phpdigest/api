<?php

declare(strict_types=1);

use App\Api\UI\Controller\Admin\LinkController;
use App\Api\UI\Widget\OffsetPagination;
use App\Api\UI\Widget\SourceIcon;
use App\Module\Link\Domain\Entity\Suggestion;
use Yiisoft\Html\Html;

/**
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var Yiisoft\Form\Widget\Field $field
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var \Yiisoft\Data\Paginator\OffsetPaginator<mixed, Suggestion> $paginator
 */

$this->params['breadcrumbs'] = 'Suggested links';

$this->setTitle('Suggested links');

?>
<h3>Suggested links: <?= $paginator->getTotalItems() ?></h3>
<table class="table table-hover table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>Created at</th>
            <th>URL, Description</th>
            <th class="text-end">Origin</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        /** @var Suggestion $link */
        foreach ($paginator->read() as $link) {
            $username = $link->identity->account->username ?? null;
            ?>
            <tr>
                <td class="text-nowrap fw-bold"><?= $link->id ?></td>
                <td class="text-nowrap text-muted"><?= $link->created_at->format('d-m-Y') ?></td>
                <td>
                    <?= Html::a(
                        Html::encode($link->url),
                        (null !== parse_url($link->url, PHP_URL_SCHEME) ? '' : 'https://') . $link->url,
                        ['rel' => 'nofollow noreferrer', 'target' => '_blank']
                    ) ?>
                    <span class="ms-2"><?= Html::encode($link->description) ?></span>
                </td>
                <td class="text-end">
                    <?= $username !== null ? sprintf("@<b>%s</b>", Html::encode($username)) : '' ?>
                    <?= SourceIcon::render($link->source) ?>
                </td>
                <td class="text-end"></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?= OffsetPagination::widget()
    ->paginator($paginator)
    ->urlGenerator(static fn ($page) => $url->generate(LinkController::PAGE_SUGGESTION_TABLE, ['page' => $page]));
