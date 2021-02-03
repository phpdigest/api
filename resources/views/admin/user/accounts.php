<?php

declare(strict_types=1);

use App\Api\UI\Controller\Admin\LinkController;
use App\Api\UI\Widget\OffsetPagination;
use App\Module\User\Domain\Entity\Account;
use Yiisoft\Html\Html;

/**
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var Yiisoft\Form\Widget\Field $field
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var \Yiisoft\Data\Paginator\OffsetPaginator<mixed, Account> $paginator
 */

$this->params['breadcrumbs'] = 'Accounts';

$this->setTitle('Accounts');

?>
    <h3>Total accounts: <?= $paginator->getTotalItems() ?></h3>
    <table class="table table-hover table-sm">
        <thead>
        <tr>
            <th title="Identity id">ID</th>
            <th>URL</th>
            <th class="text-end">Created at</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /** @var Account $account */
        foreach ($paginator->read() as $account) {
            ?>
            <tr>
                <td class="text-nowrap fw-bold"><?= $account->identity_id ?></td>
                <td><?= Html::encode($account->username) ?></td>
                <td class="text-nowrap text-muted text-end"><?= $account->created_at->format('d-m-Y') ?></td>
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
