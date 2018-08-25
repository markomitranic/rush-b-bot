<?php

namespace App\DataFixtures;

use App\Entity\Lecture;
use App\Entity\Location;
use App\Entity\Speaker;
use App\Repository\LocationRepository;
use App\Repository\SpeakerRepository;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LectureFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        for ($i = 0; $i < 22; $i++) {

            /** @var SpeakerRepository $speakerRepository */
            $speakerRepository = $manager->getRepository('App:Speaker');

            /** @var Speaker $speaker */
            $speaker = $speakerRepository->find(rand(1, 18));

            /** @var LocationRepository $locationRepository */
            $locationRepository = $manager->getRepository('App:Location');

            /** @var Location $location */
            $location = $locationRepository->find(rand(1, 2));

            $lecture = new Lecture();
            $lecture->setSpeaker($speaker);
            $lecture->setDate($faker->dateTimeBetween('-1 days', '+1 days'));
            $lecture->setDescription($faker->text(250));
            $lecture->setPhotoUrl($faker->imageUrl(300, 300));
            $lecture->setLocation($location);
            $manager->persist($lecture);

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
            SpeakerFixture::class,
            LocationFixture::class
        ];
    }
}