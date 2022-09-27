<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Course;
use DateTimeImmutable;
use App\Entity\Formation;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\FormationFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CourseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("fr-FR");

        $teacher = new User;
        $teacher->setFirstName("Gille")
            ->setLastname("Enee")
            ->setRole("ADMIN")
            ->setCreatedAt(new DateTimeImmutable());
        $manager->persist($teacher);


        $formation = $manager->getRepository(Formation::class)->findOneBy(["name" => "LP MIAW"]);

        $course = new Course;
        $course->setName("PHP")
            ->setHours(12)
            ->setType("CM")
            ->addTeacher($teacher)
            ->setFormation($formation);


        $manager->persist($course);


        $teacher2 = new User;
        $teacher2->setFirstName("Roger")
            ->setLastname("Elon")
            ->setRole("ADMIN")
            ->setCreatedAt(new DateTimeImmutable());

        $formation2 = $manager->getRepository(Formation::class)->findOneBy(["name" => "LP CAN"]);

        $course2 = new Course;
        $course2->setName("Marketing")
            ->setHours(12)
            ->setType("TP");
        $course2->addTeacher($teacher2);
        $course2->setFormation($formation2);

        $manager->persist($teacher2);
        $manager->persist($course2);


        $formation3 = $manager->getRepository(Formation::class)->findOneBy(["name" => "BUT 1", "sector" => "GEA"]);
        for ($i = 1; $i <= 3; $i++) {
            $course3 = new Course();
            $course3->setName("test")
                ->setHours(12)
                ->setType("TP")
                ->addTeacher($teacher)
                ->setFormation($formation3)
                ->setGroupe($i);
            $manager->persist($course3);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            FormationFixtures::class
        ];
    }
}
