<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Telegram;
use App\Entity\Lecture;
use App\Entity\Speaker;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class LectureListTodayCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'lectureListToday';

    /**
     * @var string
     */
    protected $description = 'Get a short list of events that start during the next hour';

    /**
     * @var string
     */
    protected $usage = '/lectureListToday';

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
            $events = $this->getTodayEvents();
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNoEvents($data);
        } catch (\Exception $e) {
            $data['text'] = 'Uh oh, something went wrong. 🤡';
            return Request::sendMessage($data);
        }


        return $this->respondWithLecturesList($data, $events);
    }

    /**
     * @param array $data
     * @param Lecture[] $lectures
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithLecturesList(array $data, $lectures)
    {
        $data['text'] = 'Here you go:';
        $keyboard = new Keyboard([
            ['text' => 'What now? ⏱'], ['text' => 'Speakers 🔊'],
        ], [
            ['text' => 'Get Directions 🗺'], ['text' => 'Full Timetable ⛓'],
        ], [
            ['text' => '🔙']
        ]);
        $keyboard = $keyboard
            ->setResizeKeyboard(true);
        $data['reply_markup'] = $keyboard;

        Request::sendMessage($data);

        $data['text'] = 'All the lectures today.';

        $inline_keyboard = new InlineKeyboard([]);
        foreach ($lectures as $lecture) {
            $inline_keyboard->addRow(['text' => $lecture->getDisplayName(), 'callback_data' => 'command__singleLecture__'. $lecture->getId()]);
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
    private function respondWithNoEvents(array $data)
    {
        $data['text'] = 'Whoa, it seems that there are no planned events for today at all. 😐';

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'See all events today?', 'callback_query' => 'command__lectureListToday']
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;
        return Request::sendMessage($data);
    }

    /**
     * @return \App\Entity\Lecture[]
     * @throws \Exception
     * @throws ResourceNotFoundException
     */
    private function getTodayEvents()
    {
        return $this->telegram->getLectureProvider()->findLecturesInInterval(
            new \DateTimeImmutable('today 7am'),
            new \DateTimeImmutable('tomorrow 4am')
        );
    }

}