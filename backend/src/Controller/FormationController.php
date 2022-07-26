<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
