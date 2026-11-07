<?php

namespace App\OrderComparison\DirectNew;

use Illuminate\Http\JsonResponse;

class OrderController
{
    public function index(): JsonResponse
    {
        $service = new OrderService();

        return response()->json($service->placeOrder(1001, 2));
    }

    public function reservation(): JsonResponse
    {
        $service = new OrderService();

        return response()->json(
            $service->placeOrder(1001, 2, 'reservation')
        );
    }
}
