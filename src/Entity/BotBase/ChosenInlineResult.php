<?php

namespace App\Entity\BotBase;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChosenInlineResult
 *
 * @ORM\Table(name="chosen_inline_result", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class ChosenInlineResult
{
    /**
     * @var string
     *
     * @ORM\Column(name="result_id", type="string", length=255, nullable=false, options={"fixed"=true,"comment"="Identifier for this result"})
     */
    private $resultId = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Location object, user's location"})
     */
    private $location;

    /**
     * @var string|null
     *
     * @ORM\Column(name="inline_message_id", type="string", length=255, nullable=true, options={"fixed"=true,"comment"="Identifier of the sent inline message"})
     */
    private $inlineMessageId;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="text", length=65535, nullable=false, options={"comment"="The query that was used to obtain the result"})
     */
    private $query;

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
