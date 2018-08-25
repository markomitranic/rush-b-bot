<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="username", columns={"username"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_bot", type="boolean", nullable=true, options={"comment"="True if this user is a bot"})
     */
    private $isBot = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false, options={"fixed"=true,"comment"="User's first name"})
     */
    private $firstName = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="User's last name"})
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=191, nullable=true, options={"fixed"=true,"comment"="User's username"})
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="language_code", type="string", length=10, nullable=true, options={"fixed"=true,"comment"="User's system language"})
     */
    private $languageCode;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\BotBase\Chat", inversedBy="user")
     * @ORM\JoinTable(name="user_chat",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="chat_id", referencedColumnName="id")
     *   }
     * )
     */
    private $chat;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $playing;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chat = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPlaying(): ?bool
    {
        return $this->playing;
    }

    public function setPlaying(?bool $playing): self
    {
        $this->playing = $playing;

        return $this;
    }

}
