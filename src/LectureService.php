<?php

namespace App;

use App\Entity\BotBase\User;
use App\Entity\Lecture;
use App\Entity\LectureRating;
use App\Repository\LectureRatingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class LectureService
{

    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param User $user
     * @param Lecture $lecture
     * @param int $rating
     */
    public function setOpinionForLecture(User $user, Lecture $lecture, int $opinion)
    {
        /** @var LectureRatingRepository $lectureRatingRepository */
        $lectureRatingRepository = $this->om->getRepository('App:LectureRating');

        $lectureRating = $lectureRatingRepository->findExistingRating($user, $lecture);

        if (is_array($lectureRating)) {
            /** @var LectureRating $rating */
            foreach ($lectureRating as $rating) {
                $this->om->remove($rating);
            }
            $lectureRating = null;
        }

        if (!$lectureRating || is_null($lectureRating)) {
            $lectureRating = new LectureRating();
            $lectureRating->setUser($user);
            $lectureRating->setLecture($lecture);
            $lectureRating->setRating((int) $opinion);
        }

        $lectureRating->setRating((int) $opinion);

        $this->om->persist($lectureRating);
        $this->om->flush();
    }

    /**
     * @param Lecture $lecture
     * @return array|int
     * @throws \Exception
     */
    public function getConsensusForLecture(Lecture $lecture) {
        /** @var LectureRating[] $ratings */
        $ratings = $lecture->getRatings();

        if (!$ratings || is_null($ratings) || (int) count($ratings) == 0) {
            throw new \Exception('There are no ratings for this lecture.');
        }

        $consensus = [
            'rating' => 0,
            'votes' => (int) count($ratings),
        ];

        foreach ($ratings as $rating) {
            $consensus['rating'] += $rating->getRating();
        }

        $consensus['rating'] = $consensus['rating'] /  $consensus['votes'];

        return $consensus;
    }

}