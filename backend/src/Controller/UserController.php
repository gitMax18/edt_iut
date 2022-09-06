<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
