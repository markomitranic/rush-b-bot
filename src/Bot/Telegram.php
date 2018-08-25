<?php

namespace App\Bot;

use App\Service\PlayersImageService;
use App\Service\PlayersResponseService;
use Longman\TelegramBot\Telegram as VendorTelegram;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Telegram extends VendorTelegram
{

    public function __construct(
        ContainerInterface $container,
        PlayersImageService $playersImageService,
        PlayersResponseService $playersResponseService
    ) {
        parent::__construct(
            $container->getParameter('bot.apikey'),
            $container->getParameter('bot.username')
        );

        $this->uploadsPath = __DIR__.'/../../public/uploads/';

        $this->playersImageService = $playersImageService;
        $this->playersResponseService = $playersResponseService;
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
     * @var PlayersImageService
     */
    private $playersImageService;

    /**
     * @var PlayersResponseService
     */
    private $playersResponseService;

    /**
     * @return PlayersImageService
     */
    public function getPlayersImageService(): PlayersImageService
    {
        return $this->playersImageService;
    }

    /**
     * @return PlayersResponseService
     */
    public function getPlayersResponseService(): PlayersResponseService
    {
        return $this->playersResponseService;
    }
}
