<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Command;

use App\Api\Telegram\Helper\FormMaker;
use App\Api\Telegram\Telegram\Conversation\SuggestLinkConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Yiisoft\Validator\ValidatorInterface;

final class FallbackCommand implements CommandInterface
{
    private SuggestLinkConversation $suggestLinkConversation;
    private FormMaker $formMaker;
    private ValidatorInterface $validator;

    public function __construct(
        SuggestLinkConversation $suggestLinkConversation,
        FormMaker $formMaker,
        ValidatorInterface $validator
    ) {
        $this->suggestLinkConversation = $suggestLinkConversation;
        $this->formMaker = $formMaker;
        $this->validator = $validator;
    }

    public static function getName(): string
    {
        return 'fallback';
    }

    public function handle(BotMan $botMan): void
    {
        $this->handleMessage($botMan, $botMan->getMessage());
    }

    private function handleMessage(BotMan $botMan, IncomingMessage $message): void
    {
        $text = $message->getText();
        $form = $this->formMaker->makeForm($text);

        if (!$form->validate($this->validator)) {
            StartCommand::replyMainMenu($botMan);
            return;
        }

        $this->suggestLinkConversation->link = $text;
        $botMan->startConversation($this->suggestLinkConversation);
    }
}
