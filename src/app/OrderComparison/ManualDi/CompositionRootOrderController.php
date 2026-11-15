<?php

namespace App\OrderComparison\ManualDi;

use Illuminate\Http\JsonResponse;

class CompositionRootOrderController
{
    public function __construct(
        private OrderServiceInterface $normalOrderService,
        private OrderServiceInterface $orderService,
        private OrderServiceInterface $loggingOrderService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->normalOrderService->placeOrder(1001, 2)
        );
    }

    public function reservation(): JsonResponse
    {
        return response()->json(
            $this->orderService->placeOrder(1001, 6, 'reservation')
        );
    }

    public function subscription(): JsonResponse
    {
        return response()->json(
            $this->orderService->placeOrder(1001, 2, 'subscription')
        );
    }

    public function logging(): JsonResponse
    {
        return response()->json(
            $this->loggingOrderService->placeOrder(1001, 2)
        );
    }
}
