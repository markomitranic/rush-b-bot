<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\Bot\Telegram;
use App\Entity\UserSurvey;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class ProfileInfoCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'profile';

    /**
     * @var string
     */
    protected $description = 'All your data.';

    /**
     * @var string
     */
    protected $usage = '/profile';

    /**
     * @var string
     */
    protected $version = '1.1.0';

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
        if ($this->callbackQuery = $this->getCallbackQuery()) {
            $message = $this->callbackQuery->getMessage();
            $chat_id = $message->getChat()->getId();
            $user_id = $message->getChat()->getId();
        } else {
            $message = $this->getMessage();
            $chat_id = $message->getChat()->getId();
            $user_id = $this->getMessage()->getFrom()->getId();
        }

        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'Markdown';
        $data['disable_web_page_preview'] = false;

        $surveys = $this->telegram->getUserSurveyProvider()->findByUserId($user_id);
        if (!is_null($surveys) && !empty($surveys)) {
            return $this->respondWithSurveyData($surveys[0], $data);
        }

        $data['text'] = '_At the moment, we do not store any of your data, except the username:_'.PHP_EOL;
        $data['text'] .= '*'.$message->getChat()->getUsername().'*'.PHP_EOL;

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'Take the personal survey! 📊', 'callback_data' => 'command__profileSurvey'],
        ]);
        $data['reply_markup'] = $inline_keyboard;

        return Request::sendMessage($data);
    }

    /**
     * @param UserSurvey $survey
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function respondWithSurveyData(UserSurvey $survey, array $data)
    {
        $data['text'] = 'Here is all the data we have of you. You can always opt to remove it completely, by going into `Your Profile` menu.'.PHP_EOL;
        $data['text'] .= ''.PHP_EOL;
        $data['text'] .= '*Name:* ' . $survey->getUser()->__toString() . PHP_EOL;
        $data['text'] .= '*Email:* ' . $survey->getEmail() . PHP_EOL;
        $data['text'] .= '*Telegram Handle:* ' . $survey->getUser()->getUsername() . PHP_EOL;
        $data['text'] .= '*Twitter Handle:* ' . $survey->getTwitterHandle() . PHP_EOL;
        $data['text'] .= '*Age:* ' . (string) $survey->getAge() . PHP_EOL;
        $data['text'] .= '*First time at Resonate:* ' . (string) $survey->getFirstTime() . PHP_EOL;
        $data['text'] .= '*Occupation:* ' . $survey->getOccupation() . PHP_EOL;

        $keyboard = new InlineKeyboard([
            ['text' => 'Delete all Data 🗄', 'callback_data' => 'command__profileSurveyDelete'],
            ['text' => 'Redo the survey 📊', 'callback_data' => 'command__profileSurvey']
        ]);
        $data['reply_markup'] = $keyboard;

        Request::sendMessage($data);
    }
}