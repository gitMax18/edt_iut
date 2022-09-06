<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


class CourseController extends AbstractController
{
    /**
     * @Route("/api/course/create", name="add_course", methods={"POST"})
     */
    public function addCourse(ManagerRegistry $doctrine, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $content = $request->toArray();
        $json = $request->getContent();
        $teacher = $doctrine->getRepository(User::class)->find($content["teachers"]);
        $formation = $doctrine->getRepository(Formation::class)->find($content["formation"]);

        try {
            $course = $serializer->deserialize($json, Course::class, 'json');
            $course->addTeachers($teacher)
                ->setFormation($formation);

            $em->persist($course);
            $em->flush();
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }
        return $this->json([
            "success" => true,
            "courseId" => $course->getId(),
            "message" => "cours ajouté"
        ], 201);
    }

    /**
     * @Route("/api/course/{formationId}", name="get_courses", methods={"GET"})
     */
    public function getCoursesByFormation(int $formationId, ManagerRegistry $doctrine): JsonResponse
    {
        $formationRepository = $doctrine->getRepository(Formation::class)->find($formationId);
        $courses = $formationRepository->getCourses();

        return $this->json([
            "success" => true,
            "message" => "Cours récupérés",
            'courses' => $courses,
        ], 200, [], ["groups" => "course:read"]);
    }
}
