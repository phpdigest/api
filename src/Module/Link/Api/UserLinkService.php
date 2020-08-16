<?php

declare(strict_types=1);

namespace App\Module\Link\Api;

use App\Common\Domain\Entity\Identity;
use App\Module\Link\Domain\Entity\Link;
use App\Module\Link\Domain\Validation\CreateLinkForm;

interface UserLinkService
{
    public function getLink(string $url, Identity $identity): Link;
    public function createLink(CreateLinkForm $form, Identity $identity): Link;
    public function deleteLink(string $url, Identity $identity): void;
}
