<?php

namespace App\Provider;

use App\Repository\SpeakerRepository;
use Doctrine\Common\Persistence\ObjectManager;

class SpeakerProvider
{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var SpeakerRepository
     */
    private $repository;

    public function __construct(ObjectManager $om, SpeakerRepository $repository)
    {
        $this->om = $om;
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return \App\Entity\Speaker|null
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return \App\Entity\Speaker[]|null
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

}
