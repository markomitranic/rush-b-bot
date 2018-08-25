<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Telegram;
use App\Entity\Lecture;
use App\Entity\Speaker;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SpeakerSingleCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'speaker_single';

    /**
     * @var string
     */
    protected $description = 'Get information about a single speaker';

    /**
     * @var string
     */
    protected $usage = '/speaker_single';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var CallbackQuery
     */
    protected $callbackQuery;

    /**
     * Execute command
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $this->callbackQuery = $this->getCallbackQuery();

        if (!$this->callbackQuery) {
            $data = [];
            $data['chat_id'] = $this->getMessage()->getChat()->getId();
            $data['message_id'] = $this->getMessage()->getMessageId();
            $data['parse_mode'] = 'HTML';
            $data['disable_web_page_preview'] = true;
            return $this->respondWithNoSpeakerId($data);
        }

        $data = [];
        $data['chat_id'] = $this->callbackQuery->getMessage()->getChat()->getId();
        $data['message_id'] = $this->callbackQuery->getMessage()->getMessageId();
        $data['parse_mode'] = 'HTML';
        $data['disable_web_page_preview'] = true;

        $speakerId = (int) $this->telegram->commandArguments[0];
        if (!isset($speakerId) || is_null($speakerId)) {
            return $this->respondWithNoSpeakerId($data);
        }

        try {
            $speaker = $this->getSpeakerById($speakerId);
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNoSpeakerId($data);
        } catch (\Exception $e) {
            $data['text'] = 'Uh oh, something went wrong. 🤡';
            return Request::sendMessage($data);
        }


        return $this->respondWithSpeakerView($data, $speaker);
    }

    /**
     * @param array $data
     * @param Speaker $speaker
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithSpeakerView(array $data, Speaker $speaker)
    {
        Request::answerCallbackQuery(['callback_query_id' => $this->callbackQuery->getId()]);

        $data['text'] = '<strong>' . $speaker->getName() . '</strong>' . PHP_EOL;
        $data['text'] .= $speaker->getCompany() . PHP_EOL;

        $lectures = $speaker->getLectures();

        if (!is_null($lectures)) {
            $inline_keyboard = new InlineKeyboard([]);
            foreach ($lectures as $lecture) {
                $inline_keyboard->addRow(
                    [
                        'text' => $lecture->getDisplayName(),
                        'callback_data' => 'command__singleLecture__'.$lecture->getId()
                    ]
                );
            }
            $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
            $data['reply_markup'] = $inline_keyboard;
        } else {
            $data['text'] .= 'Unfortunately there are no registered lectures with this speaker.';
        }

        return Request::sendMessage($data);
    }

    /**
     * @param array $data
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithNoSpeakerId(array $data)
    {
        $data['text'] = 'Hmm, i haven\'t received any lecture IDs from you. Are you sure you are using this command right? 😐';

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'See all events today?', 'callback_query' => 'command__lectureListToday']
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;
        return Request::sendMessage($data);
    }

    /**
     * @param int $speakerId
     * @return \App\Entity\Speaker
     */
    private function getSpeakerById(int $speakerId)
    {
        return $this->telegram->getSpeakerRepository()->find($speakerId);
    }

}