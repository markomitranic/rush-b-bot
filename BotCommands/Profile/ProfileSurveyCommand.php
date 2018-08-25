<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use App\Entity\UserSurvey;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ProfileSurveyCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'profileSurvey';

    /**
     * @var string
     */
    protected $description = 'All your data.';

    /**
     * @var string
     */
    protected $usage = '/profileSurvey';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Conversation Object
     *
     * @var \Longman\TelegramBot\Conversation
     */
    protected $conversation;

    /**
     * @var Telegram
     */
    protected $telegram;

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
            return $this->surveyHandle(
                $this->getMessage(),
                $this->getMessage()->getChat(),
                $this->getMessage()->getFrom()
            );
        }

        return $this->surveyHandle(
            $this->callbackQuery->getMessage(),
            $this->callbackQuery->getMessage()->getChat(),
            $this->callbackQuery->getMessage()->getChat() // Should use $this->callbackQuery->getMessage()->getFrom(), but since we are always in a callback, use chat ID which is the same as Bot id.
        );
    }

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws TelegramException
     */
    public function surveyHandle($message, $chat, $user)
    {
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'Markdown';

        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];
        $state = 0;
        if (isset($notes['state'])) {
            $state = $notes['state'];
        } else {
            $data['text'] = 'Ok so we will use this data only internally, to get to know our attendees better. All the questions are *absolutely optional*, and you can remove your data at any time.';
            Request::sendMessage($data);
        }

        $data['reply_markup'] = Keyboard::remove(['selective' => true]);
        $result = Request::emptyResponse();

        //State machine
        switch ($state) {
            case 0:
                if ($text === '' || !is_numeric($text)) {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = '1/5 Type your age:';
                    if ($text !== '') {
                        $data['text'] = 'Type your age, must be a number:';
                    }

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['age'] = $text;
                $text         = '';

            // no break
            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = '2/5 Tell us your twitter handle:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['twitter'] = $text;
                $text             = '';

            // no break
            case 2:
                if ($text === '') {
                    $notes['state'] = 2;
                    $this->conversation->update();

                    $data['text'] = '3/5 Your email maybe? No spam, i promise:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['email'] = $text;
                $text             = '';

            // no break
            case 3:
                if ($text === '' || !in_array($text, ['Yes', 'No'], true)) {
                    $notes['state'] = 3;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(['Yes', 'No']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = '4/5 Is this your first time visiting Resonate?';
                    if ($text !== '') {
                        $data['text'] = 'Is this your first time visiting Resonate?, choose a keyboard option:';
                    }

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['first_time'] = $text;
                $data['reply_markup'] = Keyboard::remove(['selective' => true]);
                $text = '';

            // no break
            case 4:
                if ($text === '') {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = '5/5 What is your occupation:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['occupation'] = $text;
                $text             = '';

            // no break
            case 5:
                $this->conversation->update();

                $user = $this->telegram->getUserProvider()->find($user_id);

                $survey = new UserSurvey();
                $survey->setUser($user);
                $survey->setAge($this->conversation->notes['age']);
                $survey->setTwitterHandle($this->conversation->notes['twitter']);
                $survey->setEmail($this->conversation->notes['email']);
                $survey->setFirstTime(($this->conversation->notes['first_time'] === 'Yes') ? true : false);
                $survey->setOccupation($this->conversation->notes['occupation']);

                $this->telegram->getUserService()->persistSurvey($survey);

                $data['text'] = 'Here is all the data we have of you. You can always opt to remove it completely, by going into `Your Profile` menu.'.PHP_EOL;
                $data['text'] .= ''.PHP_EOL;
                $data['text'] .= '*Name:* ' . $survey->getUser()->__toString() . PHP_EOL;
                $data['text'] .= '*Email:* ' . $survey->getEmail() . PHP_EOL;
                $data['text'] .= '*Telegram Handle:* ' . $survey->getUser()->getUsername() . PHP_EOL;
                $data['text'] .= '*Twitter Handle:* ' . $survey->getTwitterHandle() . PHP_EOL;
                $data['text'] .= '*Age:* ' . (string) $survey->getAge() . PHP_EOL;
                $data['text'] .= '*First time at Resonate:* ' . (string) $survey->getFirstTime() . PHP_EOL;
                $data['text'] .= '*Occupation:* ' . $survey->getOccupation() . PHP_EOL;

                $keyboard = new Keyboard([
                    ['text' => 'What now? ⏱'], ['text' => 'Night Timetable 🌚'],
                ], [
                    ['text' => 'Tweet about us 🐦'], ['text' => 'Rate a lecture 🏅'],
                ], [
                    ['text' => 'Information Desk ℹ️']
                ]);
                $data['reply_markup'] = $keyboard;

                $this->conversation->stop();

                $result = Request::sendMessage($data);
                break;
        }

        return $result;
    }
}