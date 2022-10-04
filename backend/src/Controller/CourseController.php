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

    #[Route('/api/course/create', name: 'add_course', methods: ['POST'])]
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


    /**
     * @Route("/api/course/import", name="import_courses", methods={"POST"})
     */
    public function importCourses(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): JsonResponse
    {
        $uploadCsv = $request->files->get("courses");
        if (!$uploadCsv) {
            return $this->json([
                "success" => false,
                "message" => "Nous n'avons pas pu récupérer votre fichier CSV"
            ]);
        }

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/csv/course';
        $csvPath = $uploadCsv->move($destination, time() . '-' . $uploadCsv->getClientOriginalName());

        $i = 1;

        if (($fp = fopen($csvPath, "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ';')) != false) {
                if ($i == 1) {
                    $i++;
                } else {
                    $teacher = $doctrine->getRepository(User::class)->find($row[2]);
                    // if (!$professor) {
                    //     return $this->json([
                    //         "success" => false,
                    //         "message" => "Nous n'avons pas pu récupérer le professeur, veuillez essayer avec un autre ID"
                    //     ]);
                    // }

                    $formation = $doctrine->getRepository(Formation::class)->find($row[3]);
                    // if (!$formation) {
                    //     return $this->json([
                    //         "success" => false,
                    //         "message" => "Nous n'avons pas pu récupérer le professeur, veuillez essayer avec un autre ID"
                    //     ]);
                    // }
                    $groupeNb =  $formation->getGroupeNb();
                    if ($row[4] === "TP" || $row[4] === "TD") {
                        for ($y = 1; $y <= $groupeNb; $y++) {
                            $course = new Course();
                            $course->setName($row[0])
                                ->setHours($row[1])
                                ->addTeacher($teacher)
                                ->setFormation($formation)
                                ->setType($row[4])
                                ->setBackgroundColor($row[5])
                                ->setBorderColor($row[6])
                                ->setTextColor($row[7])
                                ->setGroupe($y);
                            $em->persist($course);
                        }
                    } else {
                        $course = new Course();
                        $course->setName($row[0])
                            ->setHours($row[1])
                            ->addTeacher($teacher)
                            ->setFormation($formation)
                            ->setType($row[4])
                            ->setBackgroundColor($row[5])
                            ->setBorderColor($row[6])
                            ->setTextColor($row[7])
                            ->setGroupe(null);
                        $em->persist($course);
                    }

                    $i++;
                }
            }
        }
        $em->flush();

        return $this->json([
            "success" => true,
            "message" => $i . " cours ajouté avec succès"
        ], 201);
    }
}
