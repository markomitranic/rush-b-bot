<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\DogApi;
use App\Logger;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

class PugBombCommand extends SystemCommand
{

    /**
     * @var string
     */
    protected $name = 'pug_bomb';

    /**
     * @var string
     */
    protected $description = 'Gimme a pug!';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $callback_query = $this->getCallbackQuery();

        if ($message) {
            $chat_id = $message->getChat()->getId();
            $user_id = $message->getFrom()->getId();
            $text = $message->getText(true);
        } elseif ($callback_query) {
            $chat_id = $callback_query->getMessage()->getChat()->getId();
            $user_id = $callback_query->getFrom()->getId();
            $text = $callback_query->getData();

            $data_callback = [];
            $data_callback ['callback_query_id'] = $callback_query->getId();
        } else {
            return Request::emptyResponse();
        }

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'HTML';
        $data['disable_web_page_preview'] = true;

        // START COMMAND CODE

        try {
            $pugImage = DogApi::getRandomPugImage();
        } catch (\Exception $e) {
            Logger::getLogger()->error($e->getMessage(), $e->getTrace());
            $data = [
                'callback_query_id' => $callback_query->getId(),
                'text'              => 'Woof. I am so, so, so sorry. I am unable to obtain a propper pug image at the moment, and we both know you wouldn\'t like an imitation.',
                'show_alert'        => true,
                'cache_time'        => 5,
            ];

            return Request::answerCallbackQuery($data);
        }

        $data['text'] = $pugImage;

        // END COMMAND CODE

        if (isset($inline_keyboard)) {
            $data['reply_markup'] = new InlineKeyboardMarkup(['inline_keyboard' => $inline_keyboard]);
        }

        if ($message) {
            return Request::sendMessage($data);
        } elseif ($callback_query) {
            Request::answerCallbackQuery($data_callback);
            $data['message_id'] = $callback_query->getMessage()->getMessageId();
            return Request::sendMessage($data);
        } else {
            return Request::emptyResponse();
        }
    }

}