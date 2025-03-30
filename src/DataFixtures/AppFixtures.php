<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $usersArray = [];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $hashedPassword = $this->hasher->hashPassword($user, 'password');
            $user->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setPassword($hashedPassword)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
            ;

            $manager->persist($user);
            array_push($usersArray, $user);
        }

        for ($j = 0; $j < 50; $j++) {
            $post = new Post();
            $post->setTitle($faker->sentence())
                ->setContent($faker->paragraph(10, true))
                ->setAuthor($usersArray[mt_rand(0, 9)])
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
            ;

            $manager->persist($post);

            for ($k = 0; $k < mt_rand(1, 10); $k++) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph(mt_rand(1, 5)))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                    ->setAuthor($usersArray[mt_rand(0, 9)])
                    ->setPost($post)
                ;

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
