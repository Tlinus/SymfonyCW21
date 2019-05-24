<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Faker;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setEmail("admin@yopmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "azertyuiop"));
        $user->setLastName($faker->lastName());
        $user->setFirstName($faker->firstName());
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
        $this->addReference("USER_1",$user);

        $user = new User();
        $user->setEmail("author@yopmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "azertyuiop"));
        $user->setLastName($faker->lastName());
        $user->setFirstName($faker->firstName());
        $user->setRoles(['ROLE_AUTHOR']);
        $manager->persist($user);
        $manager->flush();
        $this->addReference("USER_2",$user);

        $user = new User();
        $user->setEmail("user@yopmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "azertyuiop"));
        $user->setLastName($faker->lastName());
        $user->setFirstName($faker->firstName());
        $manager->persist($user);
        $manager->flush();
        $this->addReference("USER_3",$user);
    }
}
