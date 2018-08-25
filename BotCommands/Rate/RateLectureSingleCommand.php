<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use App\Entity\Lecture;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RateLectureSingleCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'rate_lecture_single';

    /**
     * @var string
     */
    protected $description = 'Get information about a single lecture';

    /**
     * @var string
     */
    protected $usage = '/rate_lecture_single';

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
            return $this->respondWithNoLectureId($data);
        }

        $data = [];
        $data['chat_id'] = $this->callbackQuery->getMessage()->getChat()->getId();
        $data['message_id'] = $this->callbackQuery->getMessage()->getMessageId();
        $data['parse_mode'] = 'HTML';
        $data['disable_web_page_preview'] = true;

        $lectureId = (int) $this->telegram->commandArguments[0];
        if (!isset($lectureId) || is_null($lectureId)) {
            return $this->respondWithNoLectureId($data);
        }

        try {
            $lecture = $this->getLectureById($lectureId);
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNoLectureId($data);
        } catch (\Exception $e) {
            $data['text'] = 'Uh oh, something went wrong. 🤡';
            return Request::sendMessage($data);
        }


        return $this->respondWithLectureView($data, $lecture);
    }

    /**
     * @param array $data
     * @param Lecture $lecture
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithLectureView(array $data, Lecture $lecture)
    {
        Request::answerCallbackQuery(['callback_query_id' => $this->callbackQuery->getId()]);

        $imageUrl = __DIR__.'/../../public/uploads/images/lecture/'.$lecture->getPhotoUrl();
        $data['photo'] = Request::encodeFile($imageUrl);
        $keyboard = new Keyboard([
            ['text' => 'Rate another ☝🏻'], ['text' => 'Day Timetable 🌝']
        ], [
            ['text' => '🔙']
        ]);
        $keyboard = $keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $keyboard;
        Request::sendPhoto($data);

        $data['text'] = '<strong>' . $lecture->getSpeaker()->getName() . '</strong>' . PHP_EOL;
        $data['text'] .= $lecture->getSpeaker()->getCompany() . PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= '🗓 ' . $lecture->getDate()->format('l, d m - G i') . PHP_EOL;
        $data['text'] .= $lecture->getLocation()->getIcon() . ' ' . $lecture->getLocation()->getName() . PHP_EOL;
        $data['text'] .= PHP_EOL;
        $data['text'] .= $lecture->getDescription() . PHP_EOL;

        $inline_keyboard = new InlineKeyboard([
            ['text' => '1 😣', 'callback_data' => 'command__rateLectureOpinion__'.$lecture->getId().'__1'],
            ['text' => '2 😐', 'callback_data' => 'command__rateLectureOpinion__'.$lecture->getId().'__2'],
            ['text' => '3 ⭐️', 'callback_data' => 'command__rateLectureOpinion__'.$lecture->getId().'__3'],
            ['text' => '4 🌟', 'callback_data' => 'command__rateLectureOpinion__'.$lecture->getId().'__4'],
            ['text' => '5 💎', 'callback_data' => 'command__rateLectureOpinion__'.$lecture->getId().'__5'],
        ]);
        $inline_keyboard = $inline_keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $inline_keyboard;

        return Request::sendMessage($data);
    }

    /**
     * @param array $data
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithNoLectureId(array $data)
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
     * @param int $lectureId
     * @return \App\Entity\Lecture
     */
    private function getLectureById(int $lectureId)
    {
        return $this->telegram->getLectureProvider()->find($lectureId);
    }

}