<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Bot\Exceptions\UnrecognizedCommandException;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{

    /**
     * @var array
     */
    private $allowedCallbackMessages = [
        '🗡 Da' => 'da',
        '🐓 Ne' => 'ne',
        '📊 Oćemo?' => 'ocemo'
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
            return $this->generateServerErrorReply($message->getChat()->getId());
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
     * @param $chatId
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws TelegramException
     */
    private function generateInvalidCommandReply($chatId): ServerResponse
    {
        $data['chat_id'] = $chatId;
        $data['text'] = 'Ne razumem komandu. Praštaj. 😰';
        return Request::sendMessage($data);
    }


    /**
     * @param int $chatId
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function generateServerErrorReply($chatId): ServerResponse
    {
        $data['chat_id'] = $chatId;
        $data['text'] = 'Greška na serveru. Praštaj. 😰';
        return Request::sendMessage($data);
    }

}
