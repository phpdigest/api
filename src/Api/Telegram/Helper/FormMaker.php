<?php

declare(strict_types=1);

namespace App\Api\Telegram\Helper;

use App\Api\Common\Form\CreateLinkForm;
use Yiisoft\Injector\Injector;

final class FormMaker
{
    private Injector $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function makeForm(string $url, string $description = ''): CreateLinkForm
    {
        $form =  $this->injector->make(CreateLinkForm::class);
        $form->load([
            CreateLinkForm::FIELD_URL => $url,
            CreateLinkForm::FIELD_DESCRIPTION => $description,
        ], '');
        return $form;
    }
}
