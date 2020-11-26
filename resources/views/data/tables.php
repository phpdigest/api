<?php

declare(strict_types=1);

use App\Module\Link\Domain\Entity\Link;
use App\Module\User\Domain\Entity\Account;
use App\Module\User\Domain\Entity\Identity;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Html\Html;


/**
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var Yiisoft\Form\Widget\Field $field
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var DataReaderInterface<mixed, Link> $links
 * @var DataReaderInterface<mixed, Identity> $identities
 * @var DataReaderInterface<mixed, Account> $accounts
 */

$this->params['breadcrumbs'] = 'Data tables';

$this->setTitle('Data tables');

echo '<h3>Links: ' . count($links) . '</h3>';
/** @var Link $link */
foreach ($links as $link) {
    echo '<br>' . Html::encode($link->getUrl() . ': ' . $link->getDescription());
}

echo '<hr>';
echo '<h3>Identities: ' . count($identities) . '</h3>';
/** @var Identity $identity */
foreach ($identities as $identity) {
    echo '<br>' . $identity->getId() . $identity->created_at->format(' -- H:i:s d-m-Y');
}

echo '<hr>';
echo '<h3>Accounts: ' . count($accounts) . '</h3>';
/** @var Account $account */
foreach ($accounts as $account) {
    echo sprintf("<br>{$account->id} {$account->identity_id}:%s -- %s",
        $account->login,
        $account->created_at->format('H:i:s d-m-Y'));
}
