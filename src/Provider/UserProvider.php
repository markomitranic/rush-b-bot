<?php

namespace App\Provider;

use App\Entity\BotBase\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;

class UserProvider
{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(
        ObjectManager $om,
        UserRepository $repository
    ) {
        $this->om = $om;
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return User[]|null
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

}
