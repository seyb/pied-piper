<?php

namespace App\Controller;

use App\ApiResource\BookingPayload;
use App\Domain\BookedTwiceError;
use App\Domain\BookingAdded;
use App\Domain\FoodTruck;
use App\UseCases\AddFoodTruckBooking;
use App\UseCases\ListFoodTruckBooking;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class BookingsController extends AbstractController
{
    private ListFoodTruckBooking $listFoodTruckBooking;
    private AddFoodTruckBooking $addFoodTruckBooking;

    public function __construct(ListFoodTruckBooking $listFoodTruckBooking, AddFoodTruckBooking $addFoodTruckBooking)
    {
        $this->listFoodTruckBooking = $listFoodTruckBooking;
        $this->addFoodTruckBooking = $addFoodTruckBooking;
    }

    #[Route('/api/bookings', name: 'app_bookings', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $planning = $this->listFoodTruckBooking->planning();

        $planningResource = $this->mapPlanningResource($planning);
        return $this->json($planningResource, Response::HTTP_OK);
    }

    #[Route('/api/bookings', name: 'app_bookings_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] BookingPayload $payload
    ): JsonResponse
    {
        try {
            $result = $this->addFoodTruckBooking->call($payload->toBooking());
        } catch (\Throwable $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return match (true) {
            $result instanceof BookingAdded => $this->json(['message' => 'Successfully booked'], Response::HTTP_CREATED),
            default => throw new \Exception('Unexpected match value'),
        };
    }

    /**
     * @param FoodTruck[] $foodTrucks
     * @return array|string[]
     */
    private function mapFoodTruckNameResource(array $foodTrucks): array
    {
        return array_map(function (FoodTruck $foodTruck) {
            return $foodTruck->name;
        }, $foodTrucks);
    }

    /**
     * @param array $planning
     * @return array|array[]|\string[][]
     */
    public function mapPlanningResource(array $planning): array
    {
        return array_map(function (array $foodTrucks) {
            return $this->mapFoodTruckNameResource($foodTrucks);
        }, $planning);
    }
}

