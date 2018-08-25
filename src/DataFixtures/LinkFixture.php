<?php

namespace App\DataFixtures;

use App\Entity\Link;
use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LinkFixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        for ($i = 0; $i < 28; $i++) {

            /** @var SpeakerRepository $speakerRepository */
            $speakerRepository = $manager->getRepository('App:Speaker');

            /** @var Speaker $speaker */
            $speaker = $speakerRepository->find(rand(1, 18));

            $link = new Link();
            $name = ['Details 🗂', 'Website 🕸', 'Twitter 🐦', 'YouTube 📹'];
            $link->setName($name[rand(0, 2)]);
            $link->setLink($faker->url);
            $link->setSpeaker($speaker);
            $manager->persist($link);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            SpeakerFixture::class
        ];
    }
}