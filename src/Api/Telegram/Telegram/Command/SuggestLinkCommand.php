<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Command;

use App\Api\Telegram\Telegram\Conversation\SuggestLinkConversation;
use BotMan\BotMan\BotMan;

final class SuggestLinkCommand implements CommandInterface
{
    private SuggestLinkConversation $suggestLinkConversation;

    public function __construct(SuggestLinkConversation $suggestLinkConversation)
    {
        $this->suggestLinkConversation = $suggestLinkConversation;
    }

    public static function getName(): string
    {
        return 'suggest_link';
    }

    public function handle(BotMan $botMan): void
    {
        $botMan->startConversation($this->suggestLinkConversation);
    }
}
