<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

class HomeScreenKeyboardCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'home_screen_keyboard';

    /**
     * @var string
     */
    protected $description = 'Open up the homescreen keyboard.';

    /**
     * @var string
     */
    protected $usage = '/home-screen-keyboard';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var string[]
     */
    protected $cannedResponses = [
        'Going back a bit.',
        'As you wish.',
        'First keyboard is the best by far.',
        'Let\'s see',
        'Work, work.',
        'Okk',
        'Let\'s take it from the top.',
        'Smart move'
    ];

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

        $data['text'] = $this->cannedResponses[rand(0, count($this->cannedResponses) - 1)];

        $keyboard = new Keyboard([
            ['text' => 'What now? ⏱'],['text' => 'Night Timetable 🌚'],
        ],[
            ['text' => 'Tweet about us 🐦'],['text' => 'Rate a lecture 🏅'],
        ],[
            ['text' => 'Information Desk ℹ️']
        ]);
        $keyboard = $keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $keyboard;

        return Request::sendMessage($data);
    }

}
