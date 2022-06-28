<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="app_event")
     */
    public function index(): Response
    {
        return $this->json(['Hello' => 'there']);
    }
}
