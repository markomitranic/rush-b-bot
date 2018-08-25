<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * InlineQuery
 *
 * @ORM\Table(name="inline_query", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class InlineQuery
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Location of the user"})
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="text", length=65535, nullable=false, options={"comment"="Text of the query"})
     */
    private $query;

    /**
     * @var string|null
     *
     * @ORM\Column(name="offset", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Offset of the result"})
     */
    private $offset;

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
