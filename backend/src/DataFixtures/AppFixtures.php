<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Formation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use Faker\Factory;


class AppFixtures extends Fixture
{
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        // $this->faker = Factory::create("fr-FR");
    }
}
