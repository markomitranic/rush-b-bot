<?php

namespace App\Service;

class PlayersService
{

    /** @var string  */
    const IMAGE_LOCATION = __DIR__.'/../../public/assets/responded.jpg';

    /** @var int  */
    const IMAGE_BOX_SIZE = 80;

    /**
     * @return string
     * @throws \Exception
     */
    public function getPlayersStatusImage(): string
    {
        $noPlayers = $this->getNoPlayers();
        $yesPlayers = $this->getYesPlayers();
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

            if (!isset($avatar) || is_null($avatar) || !$avatar) {
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
     * @return array
     */
    private function getYesPlayers(): array
    {
        return [
            'lazanski',
            'lazanski',
            'lazanski',
            'lazanski',
            'lazanski',
            'lazan2ski',
            'lazanski'
        ];
    }

    /**
     * @return array
     */
    private function getNoPlayers(): array
    {
        return [
            'lazanski',
            'lazanski'
        ];
    }

    /**
     * @param int $yesSlots
     * @param int $noSlots
     * @return resource
     */
    private function createTrueColorImage(int $yesSlots, int $noSlots)
    {
        if ($noSlots > 0) {
            $newHeight = 2 * self::IMAGE_BOX_SIZE;
        } else {
            $newHeight = self::IMAGE_BOX_SIZE;
        }

        if ($yesSlots > $noSlots) {
            $newWidth = $yesSlots * self::IMAGE_BOX_SIZE;
        } else {
            $newWidth = $noSlots * self::IMAGE_BOX_SIZE;
        }

        return imagecreatetruecolor($newWidth, $newHeight);
    }
}
