<?php

namespace App\DataFixtures;

use App\Entity\Album;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class AlbumFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i=1; $i<=40; $i++){
            $album = new Album();
            $album->setName($faker->sentence(2));
            $album->setDescription($faker->sentence(10));
            $album->setCategory($this->getReference("CAT_".mt_rand(1,5)));
            $album->setUser($this->getReference("USER_".mt_rand(1,3)));
            $album->setState("review");
            $album->setPrice($faker->randomFloat(2, 1, 100));
            $manager->persist($album);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
            UserFixtures::class
        );
    }
}
