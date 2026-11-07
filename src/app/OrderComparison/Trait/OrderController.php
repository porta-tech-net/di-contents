<?php

namespace App\OrderComparison\Trait;

use Illuminate\Http\JsonResponse;

class OrderController
{
    public function index(): JsonResponse
    {
        $service = new OrderService(
            new StockService(),
            new PriceService(),
            new OrderRepository()
        );

        return response()->json($service->placeOrder(1001, 2));
    }
}
