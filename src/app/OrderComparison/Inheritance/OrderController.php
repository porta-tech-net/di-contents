<?php

namespace App\OrderComparison\Inheritance;

use Illuminate\Http\JsonResponse;

class OrderController
{
    public function index(): JsonResponse
    {
        // This example uses plain PHP instantiation, not container resolution.
        $service = new OrderService(
            new StockService(),
            new OrderRepository(),
            new PriceService()
        );

        return response()->json($service->placeOrder(1001, 2));
    }

    public function reservation(): JsonResponse
    {
        $service = new OrderService(
            new StockService(),
            new OrderRepository(),
            new PriceService()
        );
        return response()->json(
            $service->placeOrder(
                1001,
                2,
                'reservation'
            )
        );
    }
}
