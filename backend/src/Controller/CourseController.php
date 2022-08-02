<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CourseController extends AbstractController
{


    /**
     * @Route("/api/course/{formationId}", name="get_courses", methods={"GET"})
     */
    public function getCoursesByFormation(int $formationId, ManagerRegistry $doctrine): JsonResponse
    {
        // $events = $doctrine->getRepository(Event::class)->findBy(array(""));
        $formationRepository = $doctrine->getRepository(Formation::class)->find($formationId);
        $courses = $formationRepository->getCourses();

        return $this->json([
            'courses' => $courses,
        ], 200, [], ["groups" => "course:read"]);
    }
}
