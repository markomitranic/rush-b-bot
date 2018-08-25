<?php

namespace App\Bot;

use App\LectureService;
use App\Provider\LectureProvider;
use App\Provider\UserProvider;
use App\Provider\UserSurveyProvider;
use App\Repository\SpeakerRepository;
use App\Repository\UserSurveyRepository;
use App\UserService;
use Longman\TelegramBot\Telegram as VendorTelegram;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Telegram extends VendorTelegram
{

    public function __construct(
        ContainerInterface $container,
        LectureProvider $lectureProvider,
        LectureService $lectureService,
        UserProvider $userProvider,
        UserService $userService,
        SpeakerRepository $speakerRepository,
        UserSurveyProvider $userSurveyProvider
    ) {
        parent::__construct(
            $container->getParameter('bot.apikey'),
            $container->getParameter('bot.username')
        );

        $this->uploadsPath = __DIR__.'/../../public/uploads/';
        $this->lectureProvider = $lectureProvider;
        $this->lectureService = $lectureService;
        $this->userProvider = $userProvider;
        $this->userService = $userService;
        $this->speakerRepository = $speakerRepository;
        $this->userSurveyProvider = $userSurveyProvider;
    }

    /**
     * @var string
     */
    private $uploadsPath;

    /**
     * @var array
     */
    public $commandArguments = [];

    /**
     * @var LectureProvider
     */
    protected $lectureProvider;

    /**
     * @var LectureService
     */
    protected $lectureService;

    /**
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var SpeakerRepository
     */
    protected $speakerRepository;

    /**
     * @var UserSurveyProvider
     */
    protected $userSurveyProvider;

    /**
     * @return LectureProvider
     */
    public function getLectureProvider(): LectureProvider
    {
        return $this->lectureProvider;
    }

    /**
     * @return LectureService
     */
    public function getLectureService(): LectureService
    {
        return $this->lectureService;
    }

    /**
     * @return UserProvider
     */
    public function getUserProvider(): UserProvider
    {
        return $this->userProvider;
    }

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        return $this->userService;
    }

    /**
     * @return SpeakerRepository
     */
    public function getSpeakerRepository(): SpeakerRepository
    {
        return $this->speakerRepository;
    }

    /**
     * @return UserSurveyProvider
     */
    public function getUserSurveyProvider(): UserSurveyProvider
    {
        return $this->userSurveyProvider;
    }

}