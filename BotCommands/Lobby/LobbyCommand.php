<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class LobbyCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'lobby';

    /**
     * @var string
     */
    protected $description = 'Lobby Page';

    /**
     * @var string
     */
    protected $usage = '/lobby';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var Telegram
     */
    protected $telegram;

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        try {
            $statusImageUri = $this->telegram->getPlayersService()->getPlayersStatusImage();
        } catch (\Exception $e) {
            $data['text'] = $e->getMessage();
            Request::sendMessage($data);
        }

        $data['chat_id'] = $chat_id;
        $data['photo'] = Request::encodeFile($statusImageUri);
        Request::sendPhoto($data);
        return;
    }
}
