<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class NoResponseCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'noResponse';

    /**
     * @var string
     */
    protected $description = 'Respond if you are gonna play.';

    /**
     * @var string
     */
    protected $usage = '/noResponse';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var Telegram
     */
    protected $telegram;

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
        $user = $this->getMessage()->getFrom();
        $data['chat_id'] = $chat_id;

        try {
            $this->telegram->getPlayersResponseService()->respond($user->getId(), false);
        } catch (\Throwable $e) {
            $data['text'] = $e->getMessage();
            return Request::sendMessage($data);
        }

        try {
            $statusImageUri = $this->telegram->getPlayersImageService()->getPlayersStatusImage();
        } catch (\Exception $e) {
            $data['text'] = $e->getMessage();
            return Request::sendMessage($data);
        }

        $data['photo'] = Request::encodeFile($statusImageUri);
        return Request::sendPhoto($data);
    }
}
