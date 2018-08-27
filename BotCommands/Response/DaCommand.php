<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class DaCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'da';

    /**
     * @var string
     */
    protected $description = 'Respond if you are gonna play.';

    /**
     * @var string
     */
    protected $usage = '/da';

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
        $user = $this->getMessage()->getFrom();

        try {
            $this->telegram->getPlayersResponseService()->respond($user->getId(), true);
        } catch (\Throwable $e) {
            return $this->replyToChat($e->getMessage());
        }

        return Request::emptyResponse();
    }
}
