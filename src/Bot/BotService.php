<?php

namespace App\Bot;

use App\Logger;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\TelegramLog;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BotService
{

    const COMMANDS_PATH = __DIR__.'/../../BotCommands';

    /**
     * @var Telegram
     */
    private $api;

    /**
     * @var string
     */
    private $webhookAllowToken;

    /**
     * @var string
     */
    private $webhookUrl;

    /**
     * @var array
     */
    private $dbConfig;

    /**
     * BotService constructor.
     * @param Telegram $telegram
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(
        Telegram $telegram,
        ContainerInterface $container,
        LoggerInterface $logger
    ) {
        $this->api = $telegram;
        TelegramLog::initialize($logger);

        $this->webhookAllowToken = $container->getParameter('webhook.set.allow.token');
        $this->webhookUrl = $container->getParameter('webhook.url');
        $this->dbConfig = $this->getMysqlConfig(
            $container->getParameter('database.host'),
            $container->getParameter('database.user'),
            $container->getParameter('database.pass'),
            $container->getParameter('database.name')
        );
    }

    /**
     * @return bool
     * @throws TelegramException
     */
    public function handle()
    {
        $this->api->addCommandsPaths([self::COMMANDS_PATH]);
        $this->api->enableAdmins($this->getAdminUsers());
        $this->api->enableLimiter();
        $this->api->enableMySql($this->dbConfig);

        $this->api->handle();

        return true;
    }

    /**
     * @param string $authToken
     * @return bool
     * @throws TelegramException
     */
    public function setWebHook(string $authToken)
    {
        if (!isset($authToken) || $authToken !== $this->webhookAllowToken) {
            return false;
        }

        $this->api->setWebhook($this->webhookUrl);

        return true;
    }

    /**
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     * @return array
     */
    private function getMysqlConfig(
        $host,
        $user,
        $pass,
        $db
    ){
        return [
            'host'     => $host,
            'user'     => $user,
            'password' => $pass,
            'database' => $db,
        ];
    }

    /**
     * @return array
     */
    private function getAdminUsers()
    {
        return [
            'markomitranic'
        ];
    }

    /**
     * @return Telegram
     */
    public function getApi(): Telegram
    {
        return $this->api;
    }

}
