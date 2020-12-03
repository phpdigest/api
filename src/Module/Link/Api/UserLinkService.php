<?php

declare(strict_types=1);

namespace App\Module\Link\Api;

use App\Module\Link\Domain\Entity\Link;
use App\Api\Common\Form\CreateLinkForm;
use Yiisoft\Auth\IdentityInterface;

interface UserLinkService
{
    public function getLink(string $url, IdentityInterface $identity): Link;
    public function createLink(CreateLinkForm $form, IdentityInterface $identity): Link;
    public function deleteLink(string $url, IdentityInterface $identity): void;
}
