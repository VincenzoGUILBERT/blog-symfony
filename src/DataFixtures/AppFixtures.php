<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 50; $i++) { 
            $user = new User();
            $hashedPassword = $this->hasher->hashPassword($user, 'password');
            $user->setUsername($faker->userName())
                 ->setEmail($faker->email())
                 ->setPassword($hashedPassword)
                 ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
