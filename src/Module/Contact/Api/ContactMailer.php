<?php

namespace App\Module\Contact\Api;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Form\FormModelInterface;

/**
 * ContactMailer sends an email from the contact form
 */
interface ContactMailer
{
    public function send(FormModelInterface $form, ServerRequestInterface $request): void;
}
