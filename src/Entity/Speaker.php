<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeakerRepository")
 */
class Speaker
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @var Link[]
     * @ORM\OneToMany(targetEntity="App\Entity\Link", mappedBy="speaker")
     */
    private $links;

    /**
     * @var Lecture[]|null
     * @ORM\OneToMany(targetEntity="App\Entity\Lecture", mappedBy="speaker")
     */
    private $lectures;

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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Lecture[]|null
     */
    public function getLectures()
    {
        return $this->lectures;
    }

    /**
     * @param Lecture[]|ArrayCollection $lectures
     */
    public function setLectures(ArrayCollection $lectures): void
    {
        $this->lectures = $lectures;
    }

    /**
     * @return Link[]|null
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param Link[]|ArrayCollection $links
     */
    public function setLinks(ArrayCollection $links): void
    {
        $this->links = $links;
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
