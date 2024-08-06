<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private \Faker\Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();

            // Username and Email
            $username = 'user' . $i;
            $user->setUsername($username);
            $user->setEmail($username . '@a.a');

            // Password
            $user->setPassword('a');

            // Roles (optional, default to empty)
            $user->setRoles([]);

            // Balance
            $user->setBalance(rand(0, 10000));

            // Displayed Name
            $user->setDisplayedName($this->faker->name());

            // Avatar
            $user->setAvatar(rand(0, 2) . '.png');

            // Bio
            $user->setBio($this->faker->text(160));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
