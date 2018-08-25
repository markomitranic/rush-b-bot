<?php

namespace App\Bot;

use App\Service\PlayersService;
use Longman\TelegramBot\Telegram as VendorTelegram;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Telegram extends VendorTelegram
{

    public function __construct(
        ContainerInterface $container,
        PlayersService $playersService
    ) {
        parent::__construct(
            $container->getParameter('bot.apikey'),
            $container->getParameter('bot.username')
        );

        $this->uploadsPath = __DIR__.'/../../public/uploads/';

        $this->playersService = $playersService;
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
     * @var PlayersService
     */
    private $playersService;

    /**
     * @return PlayersService
     */
    public function getPlayersService()
    {
        return $this->playersService;
    }
}
