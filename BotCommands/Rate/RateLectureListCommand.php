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

class RateLectureListCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'rate_lecture_list';

    /**
     * @var string
     */
    protected $description = 'Get a short list of events that start during the next hour';

    /**
     * @var string
     */
    protected $usage = '/rate_lecture_list';

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
            $events = $this->getNextEvents();
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
        $data['text'] = 'I don’t think of it as a contest, but hey, every lecturer likes knowing that they are the best. 🙄' . PHP_EOL . PHP_EOL;
        $data['text'] .= 'Maybe i’ll make an emanel broch for the awardee, so they can carry it the next year.';

        $keyboard = new Keyboard([
            ['text' => 'What now? ⏱'], ['text' => 'Day Timetable 🌝'],
        ], [
            ['text' => 'Get Directions 🗺'], ['text' => 'Full Timetable ⛓'],
        ], [
            ['text' => '🔙']
        ]);
        $keyboard = $keyboard
            ->setResizeKeyboard(true);
        $data['reply_markup'] = $keyboard;

        Request::sendMessage($data);

        $data['text'] = 'Current talks:';

        $inline_keyboard = new InlineKeyboard([]);
        foreach ($lectures as $lecture) {
            $inline_keyboard->addRow(['text' => $lecture->getDisplayName(), 'callback_data' => 'command__rateLectureSingle__'. $lecture->getId()]);
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
        $data['text'] = 'Sorry pal, i don\'t see any events starting in the next hour. 😐';

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
    private function getNextEvents()
    {
        return $this->telegram->getLectureProvider()->findLecturesInInterval(
            (new \DateTimeImmutable('-7 minutes'))->setTimezone(new \DateTimeZone('Europe/Belgrade')),
            (new \DateTimeImmutable('+1 hour'))->setTimezone(new \DateTimeZone('Europe/Belgrade'))
        );
    }

}