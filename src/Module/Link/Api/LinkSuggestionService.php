<?php

declare(strict_types=1);

namespace App\Module\Link\Api;

use App\Module\Link\Domain\Entity\Suggestion;
use App\Api\Common\Form\CreateLinkForm;
use Yiisoft\Auth\IdentityInterface;

interface LinkSuggestionService
{
    public function createSuggestion(CreateLinkForm $form, IdentityInterface $identity): Suggestion;
    public function findSuggestion(string $url, IdentityInterface $identity): Suggestion;
    public function deleteSuggestion(string $url, IdentityInterface $identity): void;
}
