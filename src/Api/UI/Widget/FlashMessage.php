<?php

declare(strict_types=1);

namespace App\Api\UI\Widget;

use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Widget\Widget;
use Yiisoft\Yii\Bootstrap5\Alert;

final class FlashMessage extends Widget
{
    public const SUCCESS = 'alert-success';
    public const WARNING = 'alert-warning';
    public const DANGER = 'alert-danger';
    public const INFO = 'alert-info';

    private FlashInterface $flash;
    private bool $withoutCloseButton = false;
    private string $size = '';

    public function __construct(FlashInterface $flash)
    {
        $this->flash = $flash;
    }

    public function run(): string
    {
        $flashes = $this->flash->getAll();
        $html = '';

        foreach ($flashes as $type => $data) {
            foreach ($data as $message) {
                $html .= Alert::widget()
                    // ->heading($message['header'] ?? '')
                    ->body($message['body'] ?? '')
                    ->options([
                        'class' => $type,
                    ])
                    ->render();
            }
        }

        return $html;
    }

    public function withoutCloseButton(bool $value): self
    {
        $new = clone $this;
        $new->withoutCloseButton = $value;
        return $new;
    }

    public function size(string $value): self
    {
        $new = clone $this;
        $new->size = $value;
        return $new;
    }
}
