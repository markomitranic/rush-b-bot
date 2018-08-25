<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 */
class Link
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1300, options={ "collation": "utf8mb4_unicode_ci" })
     */
    private $link;

    /**
     * @var Speaker
     * @ORM\ManyToOne(targetEntity="App\Entity\Speaker", inversedBy="links")
     */
    private $speaker;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Speaker|null
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * @param Speaker $speaker
     */
    public function setSpeaker(Speaker $speaker): void
    {
        $this->speaker = $speaker;
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->getName() . ' ~ ' . $this->getLink();
    }
}
