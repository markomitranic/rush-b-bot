<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class FullTimeTableNightCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'full_timetable_night';

    /**
     * @var string
     */
    protected $description = 'Get a short list of events that start during the next hour';

    /**
     * @var string
     */
    protected $usage = '/full_timetable_night';

    /**
     * @var string
     */
    protected $version = '1.0.0';

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
        $data['parse_mode'] = 'HTML';
        $data['disable_web_page_preview'] = false;

        $imageUrl = __DIR__.'/../../public/assets/resonate-night.jpg';
        $data['photo'] = Request::encodeFile($imageUrl);

        return Request::sendPhoto($data);
    }

}