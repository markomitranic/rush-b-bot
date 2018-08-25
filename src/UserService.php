<?php

namespace App;


use App\Entity\BotBase\User;
use App\Entity\UserSurvey;
use App\Provider\UserProvider;
use App\Repository\UserRepository;
use App\Repository\UserSurveyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UserService
{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(
        ObjectManager $om,
        UserProvider $userProvider
    ) {
        $this->om = $om;
        $this->userProvider = $userProvider;
    }

    /**
     * @param UserSurvey $survey
     */
    public function persistSurvey(UserSurvey $survey)
    {
        $repository = $this->om->getRepository('App:UserSurvey');
        $duplicates = $repository->findBy([
            'user' => $survey->getUser()->getId()
        ]);

        foreach ($duplicates as $duplicate) {
            $this->om->remove($duplicate);
        }
        $this->om->flush();

        $this->om->persist($survey);
        $this->om->flush();
    }


    public function removeData(int $user_id)
    {
        $repository = $this->om->getRepository('App:UserSurvey');
        $duplicates = $repository->findBy([
            'user' => $user_id
        ]);

        foreach ($duplicates as $duplicate) {
            $this->om->remove($duplicate);
        }

        $this->om->flush();
    }

}