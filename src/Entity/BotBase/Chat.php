<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chat
 *
 * @ORM\Table(name="chat", indexes={@ORM\Index(name="old_id", columns={"old_id"})})
 * @ORM\Entity
 */
class Chat
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=200, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Chat (group) title, is null if chat type is private"})
     */
    private $title = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Username, for private chats, supergroups and channels if available"})
     */
    private $username;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="all_members_are_administrators", type="boolean", nullable=true, options={"comment"="True if a all members of this group are admins"})
     */
    private $allMembersAreAdministrators = '0';

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
     * @var int|null
     *
     * @ORM\Column(name="old_id", type="bigint", nullable=true, options={"comment"="Unique chat identifier, this is filled when a group is converted to a supergroup"})
     */
    private $oldId;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\BotBase\User", mappedBy="chat")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
