<?php

declare(strict_types=1);

namespace App\Module\Link\Api;

use App\Module\Link\Domain\Entity\Suggestion;
use App\Api\Common\Form\CreateLinkForm;
use Yiisoft\Auth\IdentityInterface;

interface UserLinkService
{
    public function createSuggestion(CreateLinkForm $form, IdentityInterface $identity): Suggestion;

    public function getLink(string $url, IdentityInterface $identity): Suggestion;
    public function deleteLink(string $url, IdentityInterface $identity): void;
}
