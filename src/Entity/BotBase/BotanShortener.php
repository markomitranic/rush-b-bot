<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * BotanShortener
 *
 * @ORM\Table(name="botan_shortener", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class BotanShortener
{
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=65535, nullable=false, options={"comment"="Original URL"})
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="short_url", type="string", length=255, nullable=false, options={"fixed"=true,"comment"="Shortened URL"})
     */
    private $shortUrl = '';

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


}
