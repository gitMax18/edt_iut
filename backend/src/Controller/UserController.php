<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user", name="get_users", methods={"GET"})
     */
    public function getAllUser(ManagerRegistry $doctrine): JsonResponse
    {
        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->json([
            "success" => true,
            "message" => "Utilisateur récupéré",
            "users" => $users,
        ], 200, [], ["groups" => "user:read"]);
    }


    /**
     * @Route("/api/user", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $json = $request->getContent();

        try {
            $user = $serializer->deserialize($json, User::class, 'json');
            $user->setRole("ADMIN");
            $user->setCreatedAt(new DateTimeImmutable());

            $em->persist($user);
            $em->flush();
            return $this->json([
                "success" => true,
                "message" => "Utilisateur ajouté"
            ], 201);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }
    }



    /**
     * @Route("/api/user/import", name="import_users", methods={"POST"})
     */
    public function importUsers(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $uploadCsv = $request->files->get("users");
        if (!$uploadCsv) {
            return $this->json([
                "success" => false,
                "message" => "Nous n'avons pas pu récupérer votre fichier CSV"
            ]);
        }

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/csv/user';
        $csvPath = $uploadCsv->move($destination, time() . '-' . $uploadCsv->getClientOriginalName());

        $i = 1;

        if (($fp = fopen($csvPath, "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ';')) != false) {
                if ($i == 1) {
                    $i++;
                } else {
                    $user = new User();
                    $user->setLastname($row[0])
                        ->setFirstName($row[1])
                        ->setEmail($row[2] || null)
                        ->setRole($row[3])
                        ->setCreatedAt(new DateTimeImmutable());

                    $em->persist($user);
                    $i++;
                }
            }
        }
        $em->flush();

        return $this->json([
            "success" => true,
            "message" => $i . " utilisateurs ajouté avec succès"
        ], 201);
    }
}
