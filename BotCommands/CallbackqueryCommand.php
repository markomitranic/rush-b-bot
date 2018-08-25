<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Exceptions\UnrecognizedCommandException;
use App\Bot\Telegram;
use App\Logger;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{

    /**
     * @var array
     */
    private $allowedCallbackCommands = [
        'pugBomb' => 'pugBomb',
        'profileSurvey' => 'profileSurvey',
        'singleLecture' => 'singleLecture',
        'rateLectureSingle' => 'rateLectureSingle',
        'rateLectureOpinion' => 'rateLectureOpinion',
        'speakerSingle' => 'speakerSingle',
        'lectureListToday' => 'lectureListToday',
        'profileSurveyDelete' => 'profileSurveyDelete'
    ];

    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function execute()
    {
        $callback_query = $this->getCallbackQuery();
        $data = $callback_query->getData();

        $command = explode('__', $data);
        $callbackType = $command[0];
        $callbackName = $command[1];
        $callbackArguments =  array_splice($command, 2);

        try {

            switch ($callbackType) {
                case 'command':
                    return $this->executeCommandByName($callbackName, $callbackArguments);
                default:
                    return $this->generateInvalidCommandReply($callback_query);
            }

        } catch (\Exception $e) {
            Logger::getLogger()->error($e->getMessage());
            return $this->generateInvalidCommandReply($callback_query);
        }
    }

    /**
     * @param string $commandName
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     * @throws UnrecognizedCommandException
     */
    private function executeCommandByName(string $commandName, array $arguments)
    {
        if (!isset($this->allowedCallbackCommands[$commandName])
            || !$this->getTelegram()->getCommandObject($this->allowedCallbackCommands[$commandName])) {
            throw new UnrecognizedCommandException();
        }

        $this->getTelegram()->commandArguments = $arguments;
        return $this->getTelegram()->executeCommand($this->allowedCallbackCommands[$commandName]);
    }

    /**
     * @param CallbackQuery $callback_query
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    private function generateInvalidCommandReply(CallbackQuery $callback_query)
    {
        $data = [];
        $data['callback_query_id'] = $callback_query->getId();
        $data['text'] = 'Invalid request!';
        $data['show_alert'] = true;
        return Request::answerCallbackQuery($data);
    }

}
