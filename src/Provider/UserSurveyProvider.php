<?php

namespace App\Provider;

use App\Repository\UserSurveyRepository;

class UserSurveyProvider
{

    /**
     * @var UserSurveyRepository
     */
    private $repository;

    public function __construct(UserSurveyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $userId
     * @return \App\Entity\UserSurvey[]
     */
    public function findByUserId(int $userId)
    {
        return $this->repository->findBy([
            'user' => $userId
        ]);
    }

}