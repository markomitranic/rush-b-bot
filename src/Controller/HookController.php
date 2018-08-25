<?php

namespace App\Controller;

use App\Bot\BotService;
use Doctrine\Common\Persistence\ObjectManager;
use Longman\TelegramBot\Exception\TelegramException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HookController extends Controller
{

    /**
     * @var BotService
     */
    private $bot;

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        BotService $bot,
        LoggerInterface $logger,
        ObjectManager $om
    ) {
        $this->logger = $logger;
        $this->bot = $bot;
        $this->om = $om;
    }

    /**
     * @return Response
     */
    public function hook()
    {
        try {
            $this->bot->handle();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return new Response('Not Ok', 500);
        }

        return new Response('Ok', 200);
    }

    /**
     * @param string $authToken
     * @return JsonResponse
     */
    public function set(string $authToken)
    {
        try {
            $webHookInfo = $this->bot->setWebHook($authToken);
        } catch (TelegramException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
        return new JsonResponse($webHookInfo);
    }

    /**
     * @return Response
     */
    public function sanity()
    {
        return new Response('is ok. u here.', 200);
    }

}
