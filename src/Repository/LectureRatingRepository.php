<?php

namespace App\Repository;

use App\Entity\BotBase\User;
use App\Entity\Lecture;
use App\Entity\LectureRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @method LectureRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method LectureRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method LectureRating[]    findAll()
 * @method LectureRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LectureRatingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LectureRating::class);
    }


    /**
     * @param User $user
     * @param Lecture $lecture
     * @return \App\Entity\Lecture[]
     */
    public function findExistingRating(
        User $user,
        Lecture $lecture
    ) {
        $qb = $this->createQueryBuilder('LectureRating');

        $results = $qb->where('LectureRating.user = :user')
            ->andWhere('LectureRating.lecture = :lecture')
            ->setParameter('user', $user->getId())
            ->setParameter('lecture', $lecture->getId())
            ->getQuery()
            ->getResult();

        if (!isset($results) || empty($results)) {
            return null;
        }

        return $results;
    }


}
