<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Telegram;
use App\Entity\Speaker;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SpeakerListCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'speaker_list';

    /**
     * @var string
     */
    protected $description = 'Get a list of all speakers.';

    /**
     * @var string
     */
    protected $usage = '/speaker_list';

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

        try {
            $speakers = $this->getAllSpeakers();
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNoSpeakers($data);
        } catch (\Exception $e) {
            $data['text'] = 'Uh oh, something went wrong. 🤡';
            return Request::sendMessage($data);
        }


        return $this->respondWithSpeakersList($data, $speakers);
    }

    /**
     * @param array $data
     * @param Speaker[] $speakers
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithSpeakersList(array $data, $speakers)
    {
        $data['text'] = 'Here comes a really long list:';

        $inline_keyboard = new InlineKeyboard([]);
        foreach ($speakers as $speaker) {
            $inline_keyboard->addRow(['text' => $speaker->getName(), 'callback_data' => 'command__speakerSingle__'. $speaker->getId()]);
        }
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;

        return Request::sendMessage($data);
    }

    /**
     * @param array $data
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithNoSpeakers(array $data)
    {
        $data['text'] = 'Weird thing just happened 😐 i see no speakers in the databse.';

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'See all events today?', 'callback_query' => 'command__lectureListToday']
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;
        return Request::sendMessage($data);
    }

    /**
     * @return Speaker[]
     * @throws \Exception
     * @throws ResourceNotFoundException
     */
    private function getAllSpeakers()
    {
        return $this->telegram->getSpeakerRepository()->findAll();
    }

}