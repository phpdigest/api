<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Command;

use App\Api\Telegram\Telegram\ChatConfig;
use App\Api\Telegram\Telegram\Conversation\SuggestLinkConversation;
use BotMan\BotMan\BotMan;

final class SuggestLinkCommand implements CommandInterface
{
    public const COMMAND = '/suggest_link';

    private SuggestLinkConversation $suggestLinkConversation;
    private ChatConfig $chatConfig;

    public function __construct(
        SuggestLinkConversation $suggestLinkConversation,
        ChatConfig $chatConfig
    ) {
        $this->suggestLinkConversation = $suggestLinkConversation;
        $this->chatConfig = $chatConfig;
    }

    public static function getName(): string
    {
        return 'suggest_link';
    }

    public function handle(BotMan $botMan): void
    {
        // delete message
        if ($this->chatConfig->cleanMode) {
            $message_id = $botMan->getMessage()->getPayload()['message_id'] ?? null;
            $botMan->sendRequest('deleteMessage', ['message_id' => $message_id]);
        }

        $botMan->startConversation($this->suggestLinkConversation);
    }
}
