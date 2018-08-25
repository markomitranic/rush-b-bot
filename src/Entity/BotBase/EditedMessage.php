<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * EditedMessage
 *
 * @ORM\Table(name="edited_message", indexes={@ORM\Index(name="chat_id", columns={"chat_id"}), @ORM\Index(name="message_id", columns={"message_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class EditedMessage
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="message_id", type="bigint", nullable=true, options={"unsigned"=true,"comment"="Unique message identifier"})
     */
    private $messageId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="edit_date", type="datetime", nullable=true, options={"comment"="Date the message was edited in timestamp format"})
     */
    private $editDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=true, options={"comment"="For text messages, the actual UTF-8 text of the message max message length 4096 char utf8"})
     */
    private $text;

    /**
     * @var string|null
     *
     * @ORM\Column(name="entities", type="text", length=65535, nullable=true, options={"comment"="For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text"})
     */
    private $entities;

    /**
     * @var string|null
     *
     * @ORM\Column(name="caption", type="text", length=65535, nullable=true, options={"comment"="For message with caption, the actual UTF-8 text of the caption"})
     */
    private $caption;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App\Entity\BotBase\Chat
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\Chat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat_id", referencedColumnName="id")
     * })
     */
    private $chat;

    /**
     * @var \App\Entity\BotBase\User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


}
