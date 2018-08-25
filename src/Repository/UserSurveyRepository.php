<?php

namespace App\Repository;

use App\Entity\UserSurvey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserSurvey|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSurvey|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSurvey[]    findAll()
 * @method UserSurvey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSurveyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserSurvey::class);
    }
}
