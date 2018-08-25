<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use http\Exception\BadConversionException;
use Psr\Log\LoggerInterface;
use stdClass;

class DogApi
{

    const BASE_URI = 'https://dog.ceo/api/';
    const PUG_RANDOM = 'breed/pug/images/random';
    const MANY_PUGS = 'breed/pug/images';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getRandomPugImage()
    {
        $response = $this->apiFetch(self::PUG_RANDOM);
        return $response->message;
    }

    /**
     * @param int $howMany
     * @return array
     * @throws Exception
     */
    public function getManyPugImages(int $howMany = 3)
    {
        $response = $this->apiFetch(self::MANY_PUGS);
        $images = array_slice($response->message, $howMany);
        return $images;
    }

    /**
     * @param string $url
     * @return stdClass
     * @throws Exception
     */
    public function apiFetch(string $url)
    {
        try {
            $output = self::getConnection()
                ->request('GET', $url)
                ->getBody();
        } catch (GuzzleException $e) {
            $this->logger->error('Guzzle exception: '.$e->getMessage());
            throw new Exception('Server response arrived but not successful.', 500);
        }

        $output = json_decode($output);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('Unable to deserialize server response.', $output);
            throw new BadConversionException('Unable to deserialize server response.', 500);
        }

        if ($output->status != 'success') {
            $this->logger->error('Server response arrived but not successful.', $output);
            throw new Exception('Server response arrived but not successful.', 500);
        }

        return $output;
    }

    /**
     * @return Client
     */
    private static function getConnection()
    {
        return new Client([
            'base_uri' => self::BASE_URI
        ]);
    }

    private static function curl($url, $cookie = false, $post = false, $header = false, $follow_location = false, $referer=false,$proxy=false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow_location);
        if ($cookie) {
            curl_setopt ($ch, CURLOPT_COOKIE, $cookie);
        }
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $response = curl_exec ($ch);
        curl_close($ch);
        return $response;
    }

}
