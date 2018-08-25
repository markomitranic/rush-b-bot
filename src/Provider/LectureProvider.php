<?php

namespace App\Provider;

use App\Repository\LectureRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class LectureProvider
{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var LectureRepository
     */
    private $repository;

    public function __construct(ObjectManager $om, LectureRepository $repository)
    {
        $this->om = $om;
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return \App\Entity\Lecture|null
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param \DateTimeImmutable $from Inclusive
     * @param \DateTimeImmutable $to Exclusive
     * @return \App\Entity\Lecture[]
     * @throws ResourceNotFoundException
     */
    public function findLecturesInInterval(
        \DateTimeImmutable $from,
        \DateTimeImmutable $to
    ) {
        $qb = $this->repository->createQueryBuilder('Lecture');

        $results = $qb->where('Lecture.date >= :now')
            ->andWhere('Lecture.date < :nextHour')
            ->setParameter('now', $from)
            ->setParameter('nextHour', $to)
            ->orderBy('Lecture.date')
            ->getQuery()
            ->getResult();

        if (!isset($results) || empty($results)) {
            throw new ResourceNotFoundException('No lectures during the next hour.');
        }

        return $results;
    }

}