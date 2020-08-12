<?php

declare(strict_types=1);

/** @var \App\Common\Application\ApplicationParameters $applicationParameters */

use Yiisoft\Security\Random;

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="title">Hello World</h1>

<p class="subtitle">My first website with <strong>Yii 3.0</strong>!</p>

<a href="https://github.com/yiisoft/docs/tree/master/guide/en" target="_blank" rel="noopener">
    <p class="subtitle is-italic" style="color:#247ba0">Read the docs.</strong></p>
</a>

<ul>
    <li><a href="/api/link?url=http%3A%2F%2Fdigest-api%2Fapi">link</a> </li>
    <li><a href="/api/user?token=<?= urlencode(Random::string(128)) ?>">get user (random token)</a> </li>
    <li>
        <form action="/api/user" method="post"><button>Create user</button></form>
    </li>
</ul>
