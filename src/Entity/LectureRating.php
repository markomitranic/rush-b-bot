<?php

namespace App\Entity;

use App\Entity\BotBase\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LectureRatingRepository")
 */
class LectureRating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\BotBase\User", inversedBy="ratings")
     */
    private $user;

    /**
     * @var Lecture
     * @ORM\ManyToOne(targetEntity="App\Entity\Lecture", inversedBy="ratings")
     */
    private $lecture;

    public function getId()
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Lecture
     */
    public function getLecture(): Lecture
    {
        return $this->lecture;
    }

    /**
     * @param Lecture $lecture
     */
    public function setLecture(Lecture $lecture): void
    {
        $this->lecture = $lecture;
    }
}
