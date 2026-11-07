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
}
