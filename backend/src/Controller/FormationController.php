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
            "success" => true,
            "message" => "Formation ajouter avec success",
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
                "success" => false,
                "message" => "Veuillez ajouter un responsable"
            ]);
        }

        try {
            $formation = $serializer->deserialize($json, Formation::class, 'json');
            $formation->setResponsable($responsable);

            $em->persist($formation);
            $em->flush();
            return $this->json([
                "success" => true,
                "formationId" => $formation->getId(),
                "message" => "Formation ajouté"
            ], 201);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
