<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Exceptions\UnrecognizedCommandException;
use App\Logger;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericMessageCommand extends SystemCommand
{

    /**
     * @var array
     */
    private $allowedCallbackMessages = [
        '🔙' => 'homeScreenKeyboard',
        'Information Desk ℹ️' => 'infoDesk',
        'Tweet about us 🐦' => 'tweetAboutUs',
        'What now? ⏱' => 'WhatNow',
        'Upcoming Talks ☝🏻' => 'WhatNow',
        'Get Directions 🗺' => 'getDirections',
        'Full Timetable ⛓' => 'fullTimetable',
        'Lectures Today 🏬' => 'lectureListToday',
        'Rate a lecture 🏅' => 'rateLectureList',
        'Rate another ☝🏻' => 'rateLectureList',
        'Speakers 🔊' => 'speakerList',
        'Social Media 🎎' => 'socialMedia',
        'About Feshbach 🤖' => 'aboutBot',
        'Your profile 🤷🏽‍♀️' => 'profileInfo',
        'Night Timetable 🌚' => 'fullTimetableNight',
        'Day Timetable 🌝' => 'fullTimetable'
    ];

    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Reply to a generic message';

    /**
     * @var string
     */
    protected $version = '1.1.2';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $data = $message->getText();

        try {
            return $this->executeCommandByName($data);
        } catch (UnrecognizedCommandException $e) {
            try {
                return $this->handleConversation();
            } catch(\Exception $e) {
                return $this->generateInvalidCommandReply($message->getChat()->getId());
            }
        } catch (\Exception $e) {
            return $this->generateServerErrorReply($message->getChat()->getId(), $e);
        }
    }

    /**
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function handleConversation()
    {
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->telegram->executeCommand($command);
        }

        $conversation->stop();
        throw new TelegramException('The message is not a conversation');
    }

    /**
     * @param string $commandName
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     * @throws UnrecognizedCommandException
     */
    private function executeCommandByName(string $commandName)
    {
        if (!array_key_exists($commandName, $this->allowedCallbackMessages)
            || !$this->getTelegram()->getCommandObject($this->allowedCallbackMessages[$commandName])) {
            throw new UnrecognizedCommandException();
        }

        return $this->getTelegram()->executeCommand($this->allowedCallbackMessages[$commandName]);
    }

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function generateInvalidCommandReply($chatId)
    {
        $data['chat_id'] = $chatId;
        $data['text'] = 'Ugh, didn\'t quite understand that, sorry.';
        return Request::sendMessage($data);
    }


    /**
     * @param int $chatId
     * @param \Exception $e
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function generateServerErrorReply($chatId, \Exception $e)
    {
        Logger::getLogger()->error($e->getMessage());
        $data['chat_id'] = $chatId;
        $data['text'] = 'A server error occured. So ashamed. 😰';
        return Request::sendMessage($data);
    }

}
