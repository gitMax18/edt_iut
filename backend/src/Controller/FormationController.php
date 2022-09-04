<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class FormationController extends AbstractController
{
    /**
     * @Route("/api/formation", name="get_formations", methods={"GET"})
     */
    public function getAllFormation(ManagerRegistry $doctrine): JsonResponse
    {

        $formations = $doctrine->getRepository(Formation::class)->findAll();

        return $this->json([
            "formations" => $formations,
        ], 200, [], ["groups" => "formation:read"]);
    }

    /**
     * @Route("/api/formation", name="add_formation", methods={"POST"})
     */
    public function addFormation(ManagerRegistry $doctrine, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $content = $request->toArray();
        $json = $request->getContent();

        $responsable = $doctrine->getRepository(User::class)->find($content["responsable"]);

        if (!$responsable) {
            return $this->json([
                "status" => "fail",
                "message" => "Veuillez ajouter un responsable"
            ]);
        }

        try {
            $formation = $serializer->deserialize($json, Formation::class, 'json');
            $formation->setResponsable($responsable);

            $em->persist($formation);
            $em->flush();
            return $this->json([
                "status" => "succes",
                "formationId" => $formation->getId(),
                "message" => "formation created"
            ], 201);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
