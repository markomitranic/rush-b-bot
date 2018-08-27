<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class OcemoCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'ocemo';

    /**
     * @var string
     */
    protected $description = 'Poteraj ekipu da se javi.';

    /**
     * @var string
     */
    protected $usage = '/ocemo';

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
            $statusImageUri = $this->telegram->getPlayersImageService()->getPlayersStatusImage();
            $data['text'] = "[​​​​​​​​​​​](".$statusImageUri."?".rand().rand().")|";
        } catch (\Exception $e) {
            return $this->replyToChat($e->getMessage());
        }

        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'markdown';

        return Request::sendMessage($data);
    }
}
