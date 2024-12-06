<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingsController extends AbstractController
{
    #[Route('/api/bookings', name: 'app_bookings')]
    public function index(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

