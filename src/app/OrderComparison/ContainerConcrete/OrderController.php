<?php

namespace App\OrderComparison\ContainerConcrete;

use Illuminate\Http\JsonResponse;

class OrderController
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->orderService->placeOrder(1001, 2));
    }
}
