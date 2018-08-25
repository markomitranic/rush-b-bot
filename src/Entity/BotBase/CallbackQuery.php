<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallbackQuery
 *
 * @ORM\Table(name="callback_query", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="chat_id", columns={"chat_id"}), @ORM\Index(name="message_id", columns={"message_id"}), @ORM\Index(name="chat_id_2", columns={"chat_id", "message_id"})})
 * @ORM\Entity
 */
class CallbackQuery
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="inline_message_id", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Identifier of the message sent via the bot in inline mode, that originated the query"})
     */
    private $inlineMessageId;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255, nullable=false, options={"fixed"=true,"comment"="Data associated with the callback button"})
     */
    private $data = '';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment"="Entry date creation"})
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App\Entity\BotBase\User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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


}
