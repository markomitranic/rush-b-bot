<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class GetDirectionsCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'get_directions';

    /**
     * @var string
     */
    protected $description = 'Get a short list of events that start during the next hour';

    /**
     * @var string
     */
    protected $usage = '/get_directions';

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
        $data['parse_mode'] = 'Markdown';

        $data['text'] = 'So you\'d like to know where you are?'.PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= 'First of all, this would be the main venue for the event:';
        Request::sendMessage($data);

        $imageUrl = __DIR__.'/../../public/assets/MSU_WIKI.jpg';
        $data['photo'] = Request::encodeFile($imageUrl);

        Request::sendPhoto($data);

        $data['photo'] = '';
        $data['text'] = '_This year Resonate takes place in brand new venues all over Belgrade City._'.PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= 'Resonate by Day is hosted by *The Museum of Contemporary Art*, and the *SAE Institute*.'.PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= 'Resonate by Night is hosted by Dom Omladine, Club Brankow, Mladost / Ludost / Gajba / Gadost, and Radost.'.PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= '*TRANSPORT:* To get from the city centre to the Museum of Contemporary Art take buses: 94, 95, E1, E6, eko1, 67, 94 or tram/trolley 7 ,9,11,13.'.PHP_EOL;
        $data['text'] .= 'More info: www.busevi.com and www.gsp.rs';
        $data['text'] .= PHP_EOL;


        $inline_keyboard = new InlineKeyboard([
            ['text' => 'Open in Google Maps 📍', 'url' => 'https://drive.google.com/open?id=1EPvu0DSQqdDnJGmm1gz7p9WcmmAVCxc7&usp=sharing']
        ], [
            ['text' => 'Museum of Contemporary Art 🔗', 'url' => 'http://www.msub.org.rs/']
        ], [
            ['text' => 'SAE Institute 🔗', 'url' => 'https://belgrade.sae.edu/']
        ], [
            ['text' => 'Radost Grill 🔗', 'url' => 'http://radostdiscogrill.com/']
        ], [
            ['text' => 'Dom Omladine 🔗', 'url' => 'http://www.domomladine.org/']
        ], [
            ['text' => 'Club Brankow 🔗', 'url' => 'https://www.facebook.com/brankow']
        ], [
            ['text' => 'Mladost Ludost 🔗', 'url' => 'http://www.mladost-ludost.com/']
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;

        return Request::sendMessage($data);
    }

}