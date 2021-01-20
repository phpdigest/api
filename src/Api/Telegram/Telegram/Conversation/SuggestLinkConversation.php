<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Conversation;

use App\Api\Common\Form\CreateLinkForm;
use App\Api\Telegram\Helper\FormMaker;
use App\Api\Telegram\Helper\TgIdentityService;
use App\Api\Telegram\Telegram\ChatConfig;
use App\Api\Telegram\Telegram\Command\SuggestLinkCommand;
use App\Module\Link\Api\UserLinkService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yiisoft\Json\Json;

final class SuggestLinkConversation extends Conversation
{
    private const COMMAND_STOP = '/suggest_stop';
    private const COMMAND_WITHOUT_DESCRIPTION = '/no_description';

    public string $link = '';
    protected string $description = '';
    protected ?int $mainMessageId = null;
    protected ?int $lastDeletedId = null;
    protected bool $finishing = false;

    private FormMaker $formMaker;
    private UserLinkService $userLinkService;
    private TgIdentityService $identityService;
    private ChatConfig $chatConfig;

    public function __construct(
        FormMaker $formMaker,
        UserLinkService $userLinkService,
        TgIdentityService $identityService,
        ChatConfig $chatConfig
    ) {
        $this->formMaker = $formMaker;
        $this->userLinkService = $userLinkService;
        $this->identityService = $identityService;
        $this->chatConfig = $chatConfig;
    }

    public function start(): void
    {
        // Send main message
        $response = $this->getBot()->reply(
            $this->generateMainText(),
            ['parse_mode' => 'Markdown'] + $this->generateMainButtons()->toArray()
        );

        // Save main message id
        $this->lastDeletedId = $this->mainMessageId = $this->getMessageId($response);
        unset($response);

        if ($this->link === '') {
            $this->askLink();
        } else {
            $this->askDescription();
        }
    }

    public function askLink(): void
    {
        $this->link = '';
        $this->ask(
            'Укажите ссылку.',
            function (Answer $answer) {
                $this->link = $answer->getText();
                $answer_id = $answer->getMessage()->getPayload()['message_id'] ?? null;

                if ($this->link === self::COMMAND_STOP) {
                    $this->deleteMessages($this->lastDeletedId + 1);
                    $this->cancelConversation();
                    return;
                }

                $form = $this->formMaker->makeForm($this->link, $this->description);

                if (!$form->validate() && $form->hasErrors(CreateLinkForm::FIELD_URL)) {
                    $this->say($form->firstError(CreateLinkForm::FIELD_URL), ['reply_to_message_id' => $answer_id]);
                    $this->askLink();
                    return;
                }

                if (is_int($answer_id)) {
                    $this->deleteMessages($answer_id);
                }
                $this->updateMainMessage();
                $this->askDescription();
            }
        );
    }

    public function askDescription(): void
    {
        $this->description = '';
        $this->ask(
            sprintf(
                <<<MARKDOWN
                    Добавьте описание.
                    Произвольный текст, максимальная длина сообщения - %s символов.
                    MARKDOWN,
                CreateLinkForm::DESCRIPTION_MAX_LENGTH
            ),
            function (Answer $answer) {
                try {
                    $this->description = $answer->getText();
                    $answer_id = $answer->getMessage()->getPayload()['message_id'] ?? null;

                    if ($this->description === self::COMMAND_STOP) {
                        $this->deleteMessages($this->lastDeletedId + 1);
                        $this->cancelConversation();
                        return;
                    }
                    if ($this->description === self::COMMAND_WITHOUT_DESCRIPTION) {
                        $this->description = '';
                    }

                    $form = $this->formMaker->makeForm($this->link, $this->description);
                    if (!$form->validate()) {
                        $this->say(implode("\n", $form->firstErrors()), ['reply_to_message_id' => $answer_id]);
                        $this->askDescription();
                        return;
                    }

                    $this->finishing = true;
                    $this->updateMainMessage();

                    // get or create identity
                    $identity = $this->identityService->getIdentity((string)$this->getBot()->getUser()->getId());

                    // create link
                    $this->userLinkService->createLink($form, $identity);

                    $response = $this->getBot()->reply('Спасибо. Мы обработаем ваше предложение.');
                    $finishMessageId = $this->getMessageId($response);
                    unset($response);
                    if ($finishMessageId !== null) {
                        $this->deleteMessages($finishMessageId - 1);
                    }
                } catch (Throwable $e) {
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
            }
        );
    }

    public function run(): void
    {
        $this->start();
    }

    private function cancelConversation(): void
    {
        if ($this->chatConfig->cleanMode) {
            $this->getBot()->sendRequest('deleteMessage', ['message_id' => $this->mainMessageId]);
        }
    }

    private function updateMainMessage(): void
    {
        $this->getBot()->sendRequest(
            'editMessageText',
            [
                'parse_mode' => 'Markdown',
                'message_id' => $this->mainMessageId,
                'text' => $this->generateMainText(),
            ] + $this->generateMainButtons()->toArray()
        );
    }

    private function generateMainButtons(): Keyboard
    {
        if ($this->finishing) {
            $row1 = [KeyboardButton::create('Предложить ещё!')->callbackData(SuggestLinkCommand::COMMAND)];
        } else {
            $row1 = [KeyboardButton::create("✖ Отмена")->callbackData(self::COMMAND_STOP)];
            if ($this->link !== '' && $this->description === '') {
                $row1[] = KeyboardButton::create("✔ Отправить без описания")
                    ->callbackData(self::COMMAND_WITHOUT_DESCRIPTION);
            }
        }
        return Keyboard::create()
            ->type(Keyboard::TYPE_INLINE)
            ->oneTimeKeyboard(true)
            ->addRow(...$row1);
    }

    private function generateMainText(): string
    {
        $str = "🌐 *Добавление ссылки*";
        if ($this->link === '') {
            return $str;
        }
        $str .= <<<MARKDOWN

                Ссылка:
                $this->link


                MARKDOWN;
        $description = preg_replace('/(`+)/u', '`', $this->description);
        $str .= $this->description === ''
            ? "Описание отсутствует."
            : <<<MARKDOWN
                Описание:
                ```
                $description
                ```
                MARKDOWN;
        return $str;
    }

    private function deleteMessages(int $from): void
    {
        $limit = $this->lastDeletedId ?? $this->mainMessageId;
        if (!$this->chatConfig->cleanMode || $limit === null || $from <= $limit) {
            return;
        }
        for ($rm = $from; $rm > $limit; --$rm) {
            $this->getBot()->sendRequest('deleteMessage', ['message_id' => $rm]);
        }
        $this->lastDeletedId = $from;
    }

    private function getMessageId($response): ?int
    {
        if (!$response instanceof Response) {
            return null;
        }
        $message_id = Json::decode($response->getContent(), true)['result']['message_id'] ?? null;
        return is_scalar($message_id) ? (int)$message_id : null;
    }
}
