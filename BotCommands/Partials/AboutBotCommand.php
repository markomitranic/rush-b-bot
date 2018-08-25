<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class AboutBotCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'about_bot';

    /**
     * @var string
     */
    protected $description = 'About bot page';

    /**
     * @var string
     */
    protected $usage = '/about_bot';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Execute command
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'Markdown';
        $data['disable_web_page_preview'] = false;

        $data['text'] = '_In the field of physics, a Feshbach resonance, named after Herman Feshbach, is a feature of many-body systems in which a bound state is achieved if the coupling(s) between at least one internal degree of freedom and the reaction coordinates, which lead to dissociation, vanish._'.PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= 'The Feshbach Telegram bot was created as a proof of concept project, specifically for Resonate 2018. It has been gifted to Resonate Festival and all it\'s atendees'.PHP_EOL;
        $data['text'] .= 'Based on longman telegram package, created in Symfony v4, runs on Nginx and MySQL, all courtesy of DigitalOcean. The bot is not intended for commercial use, and is open-sourced and published under the MIT Licence. '.PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= '*Year:* 2018' . PHP_EOL;
        $data['text'] .= '*Author:* Homullus Studio' . PHP_EOL;
        $data['text'] .= '*Licence:* MIT Licence' . PHP_EOL;
        $data['text'] .= '*Git Repository:* https://github.com/markomitranic/feshbach-symfony-telegram-bot' . PHP_EOL;

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'Feshbach Resonance on Wikipedia 🌐', 'url' => 'https://en.wikipedia.org/wiki/Feshbach_resonance']
        ], [
            ['text' => 'Legal Disclaimer and Privacy Policy', 'callback_data' => 'command__pugBomb']
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;

        return Request::sendMessage($data);
    }
}