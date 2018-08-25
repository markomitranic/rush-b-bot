<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class SocialMediaCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'social_media';

    /**
     * @var string
     */
    protected $description = 'Here is where you can contact us';

    /**
     * @var string
     */
    protected $usage = '/social_media';

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

        $data['text'] = 'Sending you links to our social media profiles:';

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'Instagram 📸', 'url' => 'https://www.instagram.com/resonate_io/'],
            ['text' => 'Twitter 🐦', 'url' => 'https://twitter.com/resonate_io']
        ], [
            ['text' => 'Vimeo 🔲', 'url' => 'https://vimeo.com/resonateio'],
            ['text' => 'Flickr 👁‍🗨', 'url' => 'https://www.flickr.com/photos/resonateio/']
        ], [
            ['text' => 'Facebook 🔷', 'url' => 'https://www.facebook.com/resonate.io/'],
            ['text' => 'FB Event 🔹', 'url' => 'https://www.facebook.com/events/1768540736494728/']
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;

        return Request::sendMessage($data);
    }
}