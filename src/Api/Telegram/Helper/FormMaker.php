<?php

declare(strict_types=1);

namespace App\Api\Telegram\Helper;

use App\Api\Telegram\Form\SuggestLinkFrom;
use Yiisoft\Injector\Injector;

final class FormMaker
{
    private Injector $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function makeForm(string $url, string $description = ''): SuggestLinkFrom
    {
        $form =  $this->injector->make(SuggestLinkFrom::class);
        $form->load([
            SuggestLinkFrom::FIELD_URL => $url,
            SuggestLinkFrom::FIELD_DESCRIPTION => $description,
        ], '');
        return $form;
    }
}
