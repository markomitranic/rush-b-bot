<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\InputMessageContent\InputTextMessageContent;
use Longman\TelegramBot\Request;

/**
 * Inline query command
 *
 * Command that handles inline queries.
 */
class InlinequeryCommand extends SystemCommand
{

    /**
     * @var string
     */
    protected $name = 'inlinequery';

    /**
     * @var string
     */
    protected $description = 'Reply to inline query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $inline_query = $this->getInlineQuery();

        $data    = ['inline_query_id' => $inline_query->getId()];
        $results = [];

        $articles = [
            [
                'id'                    => '001',
                'title'                 => '🗡 Da',
                'input_message_content' => new InputTextMessageContent(['message_text' => '🗡 Da']),
            ],
            [
                'id'                    => '002',
                'title'                 => '🐓 Ne',
                'input_message_content' => new InputTextMessageContent(['message_text' => '🐓 Ne']),
            ],
            [
                'id'                    => '003',
                'title'                 => '📊 Oćemo?',
                'input_message_content' => new InputTextMessageContent(['message_text' => '📊 Oćemo?']),
            ],
        ];
        foreach ($articles as $article) {
            $results[] = new InlineQueryResultArticle($article);
        }

        $data['results'] = '[' . implode(',', $results) . ']';
        return Request::answerInlineQuery($data);
    }
}
