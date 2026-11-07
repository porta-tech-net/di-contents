<?php

namespace App\OrderComparison\ContainerInterface;

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
    public function reservation(): JsonResponse
    {
        return response()->json(
            $this->orderService->placeOrder(
                1001,
                2,
                'reservation'
            )
        );
    }
}
