<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Course;
use DateTimeImmutable;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class EventController extends AbstractController
{
    /**
     * @Route("/api/event", name="get_events", methods={"GET"})
     */
    public function getAll(ManagerRegistry $doctrine): JsonResponse
    {
        $events = $doctrine->getRepository(Event::class)->findAllJoinByTeacherAndCourse();

        // if (empty($events)) {
        //     return $this->json([
        //         "error" => "No events find...",
        //     ], 400);
        // }

        return $this->json([
            "success" => true,
            "message" => "event récupéré",
            "events" => $events,
        ], 200, [], ["groups" => "event:read"]);
    }


    /**
     * @Route("/api/event/{formationId}",requirements={"formationId"="\d+"} ,name="get_eventsByFormation", methods={"GET"})
     */
    public function getEventByformation(ManagerRegistry $doctrine, int $formationId): JsonResponse
    {
        $events = $doctrine->getRepository(Event::class)->findAllByFormation($formationId);

        // if (empty($events)) {
        //     return $this->json([
        //         "error" => "No events find...",
        //     ], 400);
        // }

        return $this->json([
            "success" => true,
            "message" => "cours récupéré",
            "events" => $events,
        ], 200, [], ["groups" => "event:read"]);
    }


    /**
     * @Route("/api/event", name="add_event", methods={"POST"})
     */
    public function addEvent(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, ManagerRegistry $doctrine)
    {

        $content = $request->toArray();

        $events = $doctrine->getRepository(Event::class)->findByTeacherAndDates($content["teacher"], new DateTime($content["startAt"]), new DateTime($content["endAt"]));

        if (!empty($events)) {
            return $this->json([
                "success" => false,
                "message" => "Ce professeur à déja cours pendant ces dates",
                "events" => $events
            ]);
        }

        $json = $request->getContent();

        $course = $doctrine->getRepository(Course::class)->find($content["course"]);
        $teacher = $doctrine->getRepository(User::class)->find($content["teacher"]);
        $formation = $doctrine->getRepository(Formation::class)->find($content["formation"]);

        try {
            $event = $serializer->deserialize($json, Event::class, 'json', ['groups' => 'event:read']);
            $event->setCourse($course)
                ->setTeacher($teacher)
                ->setFormation($formation);

            $em->persist($event);
            $em->flush();
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }
        return $this->json([
            "success" => true,
            "eventId" => $event->getId(),
            "message" => "cours ajouté"
        ], 201);
    }

    /**
     * @Route("/api/event/{id}", name="delete_event", methods={"DELETE"})
     */
    public function deleteEvent(ManagerRegistry $doctrine, EntityManagerInterface $em, int $id): JsonResponse
    {
        $event = $doctrine->getRepository(Event::class)->find($id);

        if (!$event) {
            return $this->json([
                "success" => false,
                "message" => "Aucun cours trouvé",
            ], 400);
        }

        $em->remove($event);
        $em->flush();

        return $this->json([
            "success" => true,
            "message" => "Cours supprimé",
        ], 200);
    }

    /**
     * @Route("/api/event/{id}", name="update_event", methods={"PUT"})
     */
    public function updateEvent(ManagerRegistry $doctrine, EntityManagerInterface $em, Request $request, int $id): JsonResponse
    {
        $content = $request->toArray();
        $repository = $doctrine->getRepository(Event::class);

        $event = $repository->find($id);
        if (!$event) {
            return $this->json([
                "success" => false,
                "message" => "Aucun event trouvé",
            ], 400);
        }
        $events = $repository->findByTeacherAndDates($content["teacher"], new DateTime($content["startAt"]), new DateTime($content["endAt"]));
        $events = array_filter($events, function ($val) use ($id) {
            return $val[0]->getId() != $id;
        });

        if (!empty($events)) {
            return $this->json([
                "success" => false,
                "message" => "Ce professeur à déja cour pendant ces dates",
                "events" => $events
            ], 200, [], ["groups" => "event:read"]);
        }

        $course = $doctrine->getRepository(Course::class)->find($content["course"]);
        $teacher = $doctrine->getRepository(User::class)->find($content["teacher"]);

        $event->setCourse($course);
        $event->setTeacher($teacher);
        $event->setClassroom($content["classroom"]);
        $event->setStartAt(new DateTimeImmutable($content["startAt"]));
        $event->setEndAt(new DateTimeImmutable($content["endAt"]));
        $em->flush();

        return $this->json([
            "success" => true,
            "message" => "Cours modifié",
        ], 200);
    }
}
