<?php

namespace App\Service;

use App\Entity\BotBase\User;
use App\Repository\BotBase\UserRepository;

class PlayersImageService
{

    /** @var string  */
    const IMAGE_LOCATION = __DIR__.'/../../public/assets/responded.jpg';

    /** @var int  */
    const IMAGE_BOX_SIZE = 80;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * PlayersImageService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPlayersStatusImage(): string
    {
        $yesPlayers = $this->getYesPlayers();
        $noPlayers = $this->getNoPlayers();

        $newImage = $this->createTrueColorImage(count($yesPlayers), count($noPlayers));

        $this->printPlayers($newImage, $yesPlayers, true);
        $this->printPlayers($newImage, $noPlayers, false);

        $success = imagejpeg($newImage, self::IMAGE_LOCATION);

        if (false === $success) {
            throw new \Exception('Unable to persist image to disk.', 500);
        }

        return self::IMAGE_LOCATION;
    }

    /**
     * @param $image
     * @param array $players
     * @param bool $firstRow
     */
    private function printPlayers(&$image, array $players, bool $firstRow = true): void
    {
        if (true === $firstRow) {
            array_unshift($players, 'yesResponse');
            $row = 0;
        } else {
            array_unshift($players, 'noResponse');
            $row = 1;
        }

        foreach ($players as $key => $playerHandle) {
            $avatar = @imagecreatefromjpeg('/usr/share/nginx/rush-b/public/assets/'.$playerHandle.'.jpg');

            if (!$avatar) {
                $avatar = imagecreatefromjpeg('/usr/share/nginx/rush-b/public/assets/noAsset.jpg');
            }

            imagecopyresampled(
                $image,
                $avatar,
                $key * self::IMAGE_BOX_SIZE,
                $row * self::IMAGE_BOX_SIZE,
                0,
                0,
                self::IMAGE_BOX_SIZE,
                self::IMAGE_BOX_SIZE,
                self::IMAGE_BOX_SIZE,
                self::IMAGE_BOX_SIZE);
        }
    }

    /**
     * @return String[]
     */
    private function getYesPlayers(): array
    {
        $playerHandles = [];

        $players = $this->userRepository->getByResponseStatus(true);

        foreach ($players as $player) {
            $playerHandles[] = $player->getId();
        }

        return $playerHandles;
    }

    /**
     * @return String[]
     */
    private function getNoPlayers(): array
    {
        $playerHandles = [];

        $players = $this->userRepository->getByResponseStatus(false);

        foreach ($players as $player) {
            if (is_null($player->getUsername())) {
                $playerHandles[] = $player->getId();
            } else {
                $playerHandles[] = $player->getUsername();
            }
        }

        return $playerHandles;
    }

    /**
     * @param int $yesSlots
     * @param int $noSlots
     * @return resource
     */
    private function createTrueColorImage(int $yesSlots, int $noSlots)
    {
        $yesSlots++;
        $noSlots++;

        $newHeight = 2 * self::IMAGE_BOX_SIZE;

        if ($yesSlots > $noSlots) {
            $newWidth = $yesSlots * self::IMAGE_BOX_SIZE;
        } else {
            $newWidth = $noSlots * self::IMAGE_BOX_SIZE;
        }

        return imagecreatetruecolor($newWidth, $newHeight);
    }
}
