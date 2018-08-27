<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class NeCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'ne';

    /**
     * @var string
     */
    protected $description = 'Nep Nep';

    /**
     * @var string
     */
    protected $usage = '/ne';

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
            $this->telegram->getPlayersResponseService()->respond($user->getId(), false);
        } catch (\Throwable $e) {
            return $this->replyToChat($e->getMessage());
        }

        return Request::emptyResponse();
    }
}
