<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class FormationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("fr-FR");

        $formation = new Formation;
        $formation->setName("LP MIAW")
            ->setSector("MMI")
            ->setYear("2022")
            ->setGroupeNb(0);

        $formation2 = new Formation;
        $formation2->setName("LP CAN")
            ->setSector("MMI")
            ->setYear("2022")
            ->setGroupeNb(0);

        $formation3 = new Formation;
        $formation3->setName("BUT 1")
            ->setSector("GEA")
            ->setYear("2022")
            ->setGroupeNb(3);

        $formation4 = new Formation;
        $formation4->setName("BUT 1")
            ->setSector("MMI")
            ->setYear("2022")
            ->setGroupeNb(2);

        $formation5 = new Formation;
        $formation5->setName("BUT 2")
            ->setSector("GEA")
            ->setYear("2022")
            ->setGroupeNb(3);

        $manager->persist($formation);
        $manager->persist($formation2);
        $manager->persist($formation3);
        $manager->persist($formation4);
        $manager->persist($formation5);

        $manager->flush();
    }
}
