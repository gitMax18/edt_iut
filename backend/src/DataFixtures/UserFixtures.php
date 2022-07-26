<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("fr-FR");

        for ($u = 0; $u < 10; $u++) {

            $user = new User;
            $user->setFirstName($this->faker->firstname())
                ->setLastname($this->faker->lastname())
                ->setRole("ADMIN")
                ->setCreatedAt(new DateTimeImmutable());
            $manager->persist($user);
            $manager->flush();
        }
    }
}
