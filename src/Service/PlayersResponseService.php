<?php

namespace App\Service;

use App\Entity\BotBase\User;
use App\Repository\BotBase\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayersResponseService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->em = $entityManager;
        $this->userRepository = $this->em->getRepository(User::class);
    }

    /**
     * @param int $userId
     * @param bool $response
     * @return User
     */
    public function respond(int $userId, bool $response): User
    {
        $user = $this->userRepository->find($userId);

        $user->setPlaying($response);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

}
