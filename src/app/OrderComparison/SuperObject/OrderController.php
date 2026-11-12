<?php

namespace App\OrderComparison\SuperObject;

use Illuminate\Http\JsonResponse;

class OrderController
{
    public function index(): JsonResponse
    {
        $app = new SuperObject();
        $service = new OrderService($app);

        return response()->json($service->placeOrder(1001, 2));
    }

    public function reservation(): JsonResponse
    {
        $service = new OrderService(new SuperObject());

        return response()->json(
            $service->placeOrder(1001, 2, 'reservation')
        );
    }

    public function subscription(): JsonResponse
    {
        $app = new SuperObject();
        $service = new OrderService($app);

        return response()->json(
            $service->placeOrder(1001, 2, 'subscription')
        );
    }
}
