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
        ], 201, [], ["groups" => "formation:read"]);
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


    /**
     * @Route("/api/formation/import", name="import_formations", methods={"POST"})
     */
    public function importUsers(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): JsonResponse
    {
        $uploadCsv = $request->files->get("formations");
        if (!$uploadCsv) {
            return $this->json([
                "success" => false,
                "message" => "Nous n'avons pas pu récupérer votre fichier CSV"
            ]);
        }

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/csv/formation';
        $csvPath = $uploadCsv->move($destination, time() . '-' . $uploadCsv->getClientOriginalName());

        $i = 0;

        if (($fp = fopen($csvPath, "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ';')) != false) {
                if ($i == 0) {
                    $i++;
                } else {
                    $responsable = $doctrine->getRepository(User::class)->find($row[3]);

                    if (!$responsable) {
                        return $this->json([
                            "success" => false,
                            "message" => "Nous n'avons pas pu récupérer le responsable de formation, veuillez essayer avec un autre ID"
                        ]);
                    }

                    $formation = new Formation();
                    $formation->setName($row[0])
                        ->setSector($row[1])
                        ->setYear($row[2])
                        ->setResponsable($responsable)
                        ->setGroupeNb($row[4]);

                    $em->persist($formation);
                    $i++;
                }
            }
        }
        $em->flush();

        return $this->json([
            "success" => true,
            "message" => $i . " formations ajouté avec succès"
        ], 201);
    }
}
