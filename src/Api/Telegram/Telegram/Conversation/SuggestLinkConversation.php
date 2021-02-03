<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Conversation;

use App\Api\Common\Form\CreateLinkForm;
use App\Api\Telegram\Helper\FormMaker;
use App\Api\Telegram\Helper\TgIdentityService;
use App\Api\Telegram\Telegram\ChatConfig;
use App\Api\Telegram\Telegram\Command\FallbackCommand;
use App\Api\Telegram\Telegram\Command\StartCommand;
use App\Api\Telegram\Telegram\Command\SuggestLinkCommand;
use App\Module\Link\Api\LinkSuggestionService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yiisoft\Injector\Injector;
use Yiisoft\Json\Json;
use Yiisoft\Validator\ValidatorInterface;

final class SuggestLinkConversation extends Conversation
{
    private const COMMAND_STOP = '/suggest_stop';
    private const COMMAND_WITHOUT_DESCRIPTION = '/no_description';

    public string $link = '';
    protected string $description = '';
    protected ?int $mainMessageId = null;
    protected ?int $lastDeletedId = null;
    /** @var null|int Max message id for remove */
    protected ?int $toDeleteMaxId = null;
    protected bool $finishing = false;

    private FormMaker $formMaker;
    private LinkSuggestionService $userLinkService;
    private TgIdentityService $identityService;
    private ChatConfig $chatConfig;
    private ValidatorInterface $validator;

    public function __construct(
        FormMaker $formMaker,
        LinkSuggestionService $userLinkService,
        TgIdentityService $identityService,
        ChatConfig $chatConfig,
        ValidatorInterface $validator
    ) {
        $this->formMaker = $formMaker;
        $this->userLinkService = $userLinkService;
        $this->identityService = $identityService;
        $this->chatConfig = $chatConfig;
        $this->validator = $validator;
    }

    public function start(): void
    {
        // Send main message
        $response = $this->getBot()->reply(
            $this->generateMainText(),
            ['parse_mode' => 'Markdown'] + $this->generateMainButtons()->toArray()
        );

        // Save main message id
        $this->toDeleteMaxId = $this->lastDeletedId = $this->mainMessageId = $this->getMessageId($response);
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
                $this->calcUsersAnswer($answer);

                if ($this->link === self::COMMAND_STOP) {
                    $this->cancelConversation();
                    return;
                }
                $form = $this->formMaker->makeForm($this->link, $this->description);

                if (!$form->validate($this->validator) && $form->hasErrors(CreateLinkForm::FIELD_URL)) {
                    $this->say($form->firstError(CreateLinkForm::FIELD_URL), ['reply_to_message_id' => $answer_id]);
                    $this->askLink();
                    return;
                }

                // Prepare state
                $deleteFrom = $this->toDeleteMaxId;
                $deleteTo = $this->lastDeletedId;
                $this->lastDeletedId = $deleteFrom;
                // Store state in a cache and ask next question
                $this->askDescription();

                $this->deleteMessages($deleteFrom, $deleteTo);
                $this->updateMainMessage();
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
                    $this->calcUsersAnswer($answer);

                    if ($this->description === self::COMMAND_STOP) {
                        $this->cancelConversation();
                        return;
                    }
                    if ($this->description === self::COMMAND_WITHOUT_DESCRIPTION) {
                        $this->description = '';
                    }

                    $form = $this->formMaker->makeForm($this->link, $this->description);
                    if (!$form->validate($this->validator)) {
                        $this->say(implode("\n", $form->firstErrors()), ['reply_to_message_id' => $answer_id]);
                        $this->askDescription();
                        return;
                    }

                    $this->finishing = true;
                    $this->updateMainMessage();

                    // get or create identity
                    $identity = $this->identityService->getIdentity((string)$this->getBot()->getUser()->getId());

                    // create link
                    $this->userLinkService->createSuggestion($form, $identity);

                    $response = $this->getBot()->reply(
                        'Спасибо. Мы обработаем ваше предложение.',
                        StartCommand::generateMainMenuButtons()->toArray()
                    );
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

    public function say($message, $additionalParameters = [])
    {
        ++$this->toDeleteMaxId;
        return parent::say($message, $additionalParameters);
    }

    public function ask($question, $next, $additionalParameters = [])
    {
        ++$this->toDeleteMaxId;
        return parent::ask($question, $next, $additionalParameters);
    }

    private function calcUsersAnswer(Answer $answer): void
    {
        $fromBot = $answer->getMessage()->getPayload()['from']['is_bot'] ?? false;
        if (!$fromBot) {
            ++$this->toDeleteMaxId;
        }
    }

    private function cancelConversation(): void
    {
        $this->deleteMessages();
        if ($this->chatConfig->cleanMode) {
            $this->getBot()->sendRequest('deleteMessage', ['message_id' => $this->mainMessageId]);
        }
        StartCommand::replyMainMenu($this->getBot());
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

    private function deleteMessages(int $from = null, int $to = null): void
    {
        $from = $from ?? $this->toDeleteMaxId;
        $to = $to ?? $this->lastDeletedId;
        if (!$this->chatConfig->cleanMode || $to === null || $from <= $to) {
            return;
        }
        $this->lastDeletedId = $from;
        for ($rm = $from; $rm > $to; --$rm) {
            $this->getBot()->sendRequest('deleteMessage', ['message_id' => $rm]);
        }
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
