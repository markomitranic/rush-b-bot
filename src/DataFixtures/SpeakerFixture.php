<?php

namespace App\DataFixtures;

use App\Entity\Speaker;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class SpeakerFixture implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        for ($i = 0; $i < 18; $i++) {
            $lecture = new Speaker();
            $name = $faker->name();
            $lecture->setName($name);
            $lecture->setCompany($faker->sentence(2, true));
            $manager->persist($lecture);
        }

        $manager->flush();
    }
}