<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * TelegramUpdate
 *
 * @ORM\Table(name="telegram_update", indexes={@ORM\Index(name="message_id", columns={"chat_id", "message_id"}), @ORM\Index(name="inline_query_id", columns={"inline_query_id"}), @ORM\Index(name="chosen_inline_result_id", columns={"chosen_inline_result_id"}), @ORM\Index(name="callback_query_id", columns={"callback_query_id"}), @ORM\Index(name="edited_message_id", columns={"edited_message_id"})})
 * @ORM\Entity
 */
class TelegramUpdate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App\Entity\BotBase\Message
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\Message")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat_id", referencedColumnName="chat_id"),
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * })
     */
    private $chat;

    /**
     * @var \App\Entity\BotBase\InlineQuery
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\InlineQuery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inline_query_id", referencedColumnName="id")
     * })
     */
    private $inlineQuery;

    /**
     * @var \App\Entity\BotBase\ChosenInlineResult
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\ChosenInlineResult")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chosen_inline_result_id", referencedColumnName="id")
     * })
     */
    private $chosenInlineResult;

    /**
     * @var \App\Entity\BotBase\CallbackQuery
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\CallbackQuery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="callback_query_id", referencedColumnName="id")
     * })
     */
    private $callbackQuery;

    /**
     * @var \App\Entity\BotBase\EditedMessage
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\EditedMessage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edited_message_id", referencedColumnName="id")
     * })
     */
    private $editedMessage;


}
