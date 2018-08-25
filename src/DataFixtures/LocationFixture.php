<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LocationFixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $location1 = new Location();
        $location1->setName('Room 1 @ Dom Omladine');
        $location1->setIcon('①');
        $location1->setDescription($faker->text(250));
        $location1->setPhoto($faker->imageUrl(300, 300));
        $manager->persist($location1);

        $location2 = new Location();
        $location2->setName('Room 2 @ Dom Omladine');
        $location2->setIcon('②');
        $location2->setDescription($faker->text(250));
        $location2->setPhoto($faker->imageUrl(300, 300));
        $manager->persist($location2);

        $manager->flush();
    }
}