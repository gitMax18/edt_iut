<?php

namespace App\Controller;

use App\Entity\Event;
use DateTime;
use DateTimeImmutable;
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
        $events = $doctrine->getRepository(Event::class)->findAll();

        // if (empty($events)) {
        //     return $this->json([
        //         "error" => "No events find...",
        //     ], 400);
        // }

        return $this->json([
            "events" => $events,
        ], 200);
    }


    /**
     * @Route("/api/event/{sector}/{formation}", name="get_eventsByFormation", methods={"GET"})
     */
    public function getEventByformation(ManagerRegistry $doctrine, string $sector, string $formation): JsonResponse
    {
        $events = $doctrine->getRepository(Event::class)->findByFormation($sector, $formation);

        // if (empty($events)) {
        //     return $this->json([
        //         "error" => "No events find...",
        //     ], 400);
        // }

        return $this->json([
            "events" => $events,
        ], 200);
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
                "status" => "error",
                "message" => "Ce professeur à déja cour pendant ces dates",
                "events" => $events
            ]);
        }

        $json = $request->getContent();
        try {
            $event = $serializer->deserialize($json, Event::class, 'json');
            $em->persist($event);
            $em->flush();
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "error" => $e->getMessage()
            ], 400);
        }
        return $this->json([
            "status" => "succes",
            "eventId" => $event->getId(),
            "message" => "event created"
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
                "message" => "No event find...",
            ], 400);
        }

        $em->remove($event);
        $em->flush();

        return $this->json([
            "message" => "event deleted",
        ], 200);
    }

    /**
     * @Route("/api/event/{id}", name="update_event", methods={"PUT"})
     */
    public function updateEvent(ManagerRegistry $doctrine, EntityManagerInterface $em, Request $request, int $id): JsonResponse
    {
        $content = $request->toArray();
        $repository = $doctrine->getRepository(Event::class);

        $events = $repository->findByTeacherAndDates($content["teacher"], new DateTime($content["startAt"]), new DateTime($content["endAt"]));

        $events = array_filter($events, function ($val) use ($id) {
            return $val->getId() != $id;
        });


        if (!empty($events)) {
            return $this->json([
                "status" => "error",
                "message" => "Ce professeur à déja cour pendant ces dates",
                "events" => $events
            ]);
        }

        $event = $repository->find($id);
        if (!$event) {
            return $this->json([
                "message" => "No event find...",
            ], 400);
        }

        $event->setCourse($content["course"]);
        $event->setClassroom($content["classroom"]);
        $event->setTeacher($content["teacher"]);
        $event->setStartAt(new DateTimeImmutable($content["startAt"]));
        $event->setEndAt(new DateTimeImmutable($content["endAt"]));

        $em->flush();

        return $this->json([
            "message" => "event updated",
        ], 200);
    }
}
