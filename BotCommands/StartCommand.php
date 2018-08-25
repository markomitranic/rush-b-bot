<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Conversation Object
     *
     * @var \Longman\TelegramBot\Conversation
     */
    protected $conversation;

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $data['chat_id'] = $chat_id;

        $text = 'Hi there!' . PHP_EOL;
        $text .= 'Welcome to Resonate.io, i will be your host for these couple of days. 🙂'. PHP_EOL;
        $data['text'] = $text;

        $keyboard = new Keyboard([
            ['text' => 'What now? ⏱'], ['text' => 'Night Timetable 🌚'],
        ], [
            ['text' => 'Tweet about us 🐦'], ['text' => 'Rate a lecture 🏅'],
        ], [
            ['text' => 'Information Desk ℹ️']
        ]);
        $data['reply_markup'] = $keyboard;

        Request::sendMessage($data);

        $text = '';
        $text .= 'You can ask me stuff via the keyboard keys, or if you are a terminal-lover, there is always the /help command to help you get started.'. PHP_EOL;
        $text .= PHP_EOL;
        $text .= 'Also, only if you are willing, i can ask you a couple of questions so I get to know you better and maybe help me draw some statistics. .. Or not, your choice.'.PHP_EOL;
        $data['text'] = $text;

        $surveys = $this->telegram->getUserSurveyProvider()->findByUserId(
            $this->getMessage()->getFrom()->getId()
        );
        if (is_null($surveys) || empty($surveys)) {
            $inline_keyboard = new InlineKeyboard([
                ['text' => 'Take the personal survey! 📊', 'callback_data' => 'command__profileSurvey'],
            ]);
            $data['reply_markup'] = $inline_keyboard;
        }

        return Request::sendMessage($data);
    }

}
