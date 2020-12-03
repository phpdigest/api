<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Conversation;

use App\Api\Common\Form\CreateLinkForm;
use App\Api\Telegram\Helper\FormMaker;
use App\Api\Telegram\Helper\TgIdentityService;
use App\Module\Link\Api\UserLinkService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

final class SuggestLinkConversation extends Conversation
{
    protected string $link = '';
    protected string $description = '';

    private FormMaker $formMaker;
    private UserLinkService $userLinkService;
    private TgIdentityService $identityService;

    public function __construct(
        FormMaker $formMaker,
        UserLinkService $userLinkService,
        TgIdentityService $identityService
    ) {
        $this->formMaker = $formMaker;
        $this->userLinkService = $userLinkService;
        $this->identityService = $identityService;
    }

    public function askLink(): void
    {
        $str = 'Введите ссылку';

        $this->ask($str, function (Answer $answer) {
            $this->link = $answer->getText();

            $form = $this->formMaker->makeForm($this->link, $this->description);

            if (!$form->validate() && $form->hasErrors(CreateLinkForm::FIELD_URL)) {
                $this->say($form->firstError(CreateLinkForm::FIELD_URL));
                return;
            }

            $this->askDescription();
        });
    }

    public function askDescription(): void
    {
        $str = <<<TEXT
        Опишите то, что хотите предложить.
        Можно добавлять ссылки и/или произвольный текст. Максимальная длина сообщения - 300 символов.
        TEXT;
        $this->ask($str, function (Answer $answer) {
            try {
                $this->description = $answer->getText();

                $form = $this->formMaker->makeForm($this->link, $this->description);
                if (!$form->validate()) {
                    $this->say(implode("\n", $form->firstErrors()));
                    return;
                }

                $this->say('Спасибо. Мы обработаем ваше предложение.');

                // get or create identity
                $identity = $this->identityService->getIdentity((string)$this->getBot()->getUser()->getId());

                // create link
                $this->userLinkService->createLink($form, $identity);

                $this->say(
                    <<<MARKDOWN
                        Ссылка: `{$this->link}`
                        Описание:
                        ```
                        {$this->description}
                        ```
                        MARKDOWN,
                    ['parse_mode' => 'Markdown']
                );
            } catch (\Throwable $e) {
                $this->say(
                    <<<ERROR
                        Error {$e->getCode()}
                        `{$e->getFile()}:{$e->getLine()}`
                        ```
                        {$e->getMessage()}
                        ```
                        ERROR,
                    ['parse_mode' => 'Markdown']
                );
            }
        });
    }

    public function run(): void
    {
        $this->askLink();
    }
}
