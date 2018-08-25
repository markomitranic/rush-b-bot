<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Telegram;
use App\Entity\Lecture;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RateLectureOpinionCommand extends UserCommand
{

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'rate_lecture_opinion';

    /**
     * @var string
     */
    protected $description = 'Get information about a single lecture';

    /**
     * @var string
     */
    protected $usage = '/rate_lecture_opinion';

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
            return Request::emptyResponse();
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


        return $this->respondWithLectureOpinionPersist($data, $lecture);
    }

    /**
     * @param array $data
     * @param Lecture $lecture
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithLectureOpinionPersist(array $data, Lecture $lecture)
    {
        Request::answerCallbackQuery(['callback_query_id' => $this->callbackQuery->getId()]);

        $newRating = $this->telegram->commandArguments[1];
        if (!isset($newRating) || is_null($newRating)) {
            return $this->respondWithNoLectureRating($data);
        }

        $this->telegram->getLectureService()->setOpinionForLecture(
            $this->telegram->getUserProvider()->find($this->callbackQuery->getFrom()->getId()),
            $lecture,
            $newRating
        );

        try {
            $consensus = $this->telegram->getLectureService()->getConsensusForLecture($lecture);
        } catch (\Exception $e) {
            $data['text'] = 'Whoops, something went wrong, there are no ratings for this lecture.';
            return Request::sendMessage($data);
        }

        $data['text'] = '<strong>Thanks for telling me what you think, im keeping tabs on everything, so the general consesnsus grade fot this lecture is '.$consensus['rating'].', based on '.$consensus['votes'].' votes.</strong>';

        return Request::sendMessage($data);
    }

    /**
     * @param array $data
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithNoLectureRating(array $data)
    {
        $data['text'] = 'Hmm, i haven\'t received any rating from you. Are you sure you are using this command right? 😐';

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'See all events today?', 'callback_query' => 'command__lectureListToday']
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