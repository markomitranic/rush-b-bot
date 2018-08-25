<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table(name="conversation", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="chat_id", columns={"chat_id"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class Conversation
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=200, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="command", type="string", length=160, nullable=true, options={"comment"="Default command to execute"})
     */
    private $command = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="notes", type="text", length=65535, nullable=true, options={"comment"="Data stored from command"})
     */
    private $notes;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment"="Entry date creation"})
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"comment"="Entry date update"})
     */
    private $updatedAt;

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
     * @var \App\Entity\BotBase\Chat
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\Chat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat_id", referencedColumnName="id")
     * })
     */
    private $chat;


}
